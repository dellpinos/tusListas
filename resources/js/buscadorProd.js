import * as helpers from './helpers';
(function () {

    if (document.querySelector('#contenedor-input')) {


        const contenedorInput = document.querySelector('#contenedor-input');
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputProductoFalso = document.querySelector('#producto-nombre-falso');
        const btnNombre = document.querySelector('#btn-nombre');
        const cardProducto = document.querySelector('#card-producto');
        const inputCodigo = document.querySelector('#producto-codigo');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado


        // Buscar por codigo - producto se cambian con un paginador, se gira (como el ejemplo del cubo)
        // El usuario puede escoger uno u otro metodo de busqueda


        btnNombre.addEventListener('click', function (e) {
            e.preventDefault();
            generarHTML();

        });

        inputProductoFalso.addEventListener('click', function () {
            // insertar html
            generarHTML();
        });

        // DOM scripting
        function generarHTML() {
            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor');

            // input real
            const inputProducto = document.createElement('INPUT');
            inputProducto.type = 'text';
            inputProducto.name = 'producto-nombre';
            inputProducto.classList.add('buscador__campo', 'buscador__campo-focus');
            inputProducto.placeholder = 'Nombre del Producto';

            if (inputProductoFalso.value !== '') {
                inputProducto.value = inputProductoFalso.value;
            }

            contenedorOpciones.appendChild(inputProducto);

            // listado de coincidencias
            const lista = document.createElement('UL');

            contenedorOpciones.appendChild(lista);
            contenedorInput.appendChild(contenedorOpciones);

            inputProducto.focus(); // cursor sobre el input

            // realizar busqueda
            inputProducto.addEventListener('input', function (e) {

                if (flag === 1 && e.target.value.length < 3) { // Reinicio el flag para volver a realizar la busqueda
                    flag = 0;
                }

                filtrarResultado(e);
            });

            contenedorInput.addEventListener('mouseleave', function () {

                inputProductoFalso.value = inputProducto.value;
                // eliminar html
                while (contenedorOpciones.firstChild) {
                    contenedorOpciones.removeChild(contenedorOpciones.firstChild);
                }
                contenedorOpciones.remove();

            });

            async function filtrarResultado(e) {
                try {
                    if (!flag) {
                        arrayCoincidencias = await findDB(e.target.value); // Almaceno la respuesta en memoria
                    }

                } catch (error) {
                    console.log(error);
                }
                if (flag) { // aqui puedo filtrar el array en memoria

                    buscarCoincidenciasMemoria(e);
                }
            }

            async function findDB(inputProducto) {

                if (inputProducto.length === 3) {
                    try {
                        const datos = new FormData();
                        datos.append('input_producto', inputProducto);

                        const url = '/api/buscador/producto';
                        const respuesta = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': tokenCSRF
                            },
                            body: datos
                        });

                        let resultado = await respuesta.json();
                        flag = 1;
                        return resultado;

                    } catch (error) {
                        console.log('El servidor no responde');
                    }
                }
            }

            function buscarCoincidenciasMemoria(e) {
                const busqueda = e.target.value;
                const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

                coincidenciasPantalla = arrayCoincidencias.filter(coincidencia => {
                    if (coincidencia.nombre.toLowerCase().search(Regex) !== -1) {
                        return coincidencia;
                    }
                });

                generarHTMLcoincidencia()
            }

            function generarHTMLcoincidencia() {

                while (lista.firstChild) {
                    lista.removeChild(lista.firstChild);
                }

                let acu = 0; // Cantidad de coincidencias

                coincidenciasPantalla.forEach(coincidencia => {

                    acu++;
                    if (acu <= 4) {

                        const sugerenciaBusqueda = document.createElement('LI');
                        sugerenciaBusqueda.textContent = coincidencia.nombre;

                        sugerenciaBusqueda.addEventListener('click', function (e) {

                            buscarProducto(coincidencia.id);
                        });

                        lista.appendChild(sugerenciaBusqueda);
                    }
                });
            }

            async function buscarProducto(id) {

                try {
                    const datos = new FormData();
                    datos.append('id', id);

                    const url = '/api/buscador/producto-individual';
                    const respuesta = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                        body: datos
                    });

                    let resultado = await respuesta.json();

                    resultado.producto.venta = helpers.redondear(resultado.producto.venta);


                    // Formatear fecha (se obtiene tal cual esta almacenada en la DB)
                    const fechaObj = new Date(resultado.precio.updated_at);
                    const mes = fechaObj.getMonth();
                    const dia = fechaObj.getDate() + 1; // Corrijo desfasaje
                    const year = fechaObj.getFullYear();

                    const fechaUTC = new Date(Date.UTC(year, mes, dia));

                    const opciones = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }
                    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);

                    if (resultado.producto.unidad_fraccion === null) {
                        resultado.producto.unidad_fraccion = '';
                    }

                    cardProducto.classList.add('producto__card-contenedor');

                    cardProducto.innerHTML = `
                    <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                    <div class=" producto__contenedor ">
                        <h3 class="producto__card-nombre">${resultado.producto.nombre}</h3>
                        <div class="producto__contenedor-precio">
                            <p class="producto__card-precio">$ ${resultado.producto.venta}<span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                        </div>
                        <div class="producto__card-info">
                            <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                            <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                            <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                            <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                        </div>
        
                    </div>
                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
                </a>
                `;
                    inputProductoFalso.value = '';
                    inputCodigo.value = '';

                } catch (error) {
                    console.log('El servidor no responde');
                }
            }
        }
    }
})();