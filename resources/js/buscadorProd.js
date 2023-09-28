import * as helpers from './helpers';
(function () {

    if (document.querySelector('#contenedor-input')) {

        const contenedorTabs = document.querySelector('#dashboard__contenedor-tabs');
        const tabs = document.querySelector('#dashboard__tabs');

        const tabTodos = document.querySelector('#dashboard__tab-todos');
        const tabProrducto = document.querySelector('#dashboard__tab-producto');
        const tabCodigo = document.querySelector('#dashboard__tab-codigo');
        const tabCategoria = document.querySelector('#dashboard__tab-categoria');
        const tabFabricante = document.querySelector('#dashboard__tab-fabricante');
        const tabProvider = document.querySelector('#dashboard__tab-provider');

        const contenedorInput = document.querySelector('#contenedor-input');
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputProductoFalso = document.querySelector('#producto-nombre-falso');
        const cardProducto = document.querySelector('#card-producto');
        const inputCodigo = document.querySelector('#producto-codigo');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado

        let tipoBusqueda = 'producto';

        tabCodigo.addEventListener('click', () => {

            // Cambiar el buscador
            // Cambiar placeholder y almacenar un "flag" para el momento en que el usuario presione en el input falso

            inputProductoFalso.placeholder = "Código del producto";
            tipoBusqueda = 'codigo';

            console.log('Buscar por Código');
        });


        // Buscar por codigo - producto se cambian con un paginador
        // El usuario puede escoger uno u otro metodo de busqueda


        inputProductoFalso.addEventListener('click', function () {

            // insertar html
            generarHTML();

        });

        // Mostrar / Ocultar tabs
        contenedorTabs.addEventListener('mouseenter', () => {
            tabs.classList.add('dashboard__tabs--activo');
        });
        contenedorTabs.addEventListener('mouseleave', () => {
            tabs.classList.remove('dashboard__tabs--activo');
        });

        // DOM scripting
        function generarHTML() {


            // Evaluar el tab con un switch ????
            switch (tipoBusqueda) {
                case "producto":
                    console.log('Buscar Producto');
                    break;
                case "codigo":
                    console.log("Buscar Código");
                    break;
                case "todos":
                    console.log('Buscar Todos');
                    break;
                case "categoria":
                    console.log("Buscar Categoria");
                    break;
                case "fabricante":
                    console.log("Buscar Fabricante");
                    break;
                case "provider":
                    console.log("Buscar Provider");
                    break;
                default:
                    console.log("Error en tipo de busqueda");
                    break;
            }

            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor');

            const iconoBuscador = document.createElement('DIV');
            iconoBuscador.innerHTML = '<i class="formulario__icono-busqueda fa-solid fa-magnifying-glass"></i>';
            iconoBuscador.classList.add('formulario__icono-busqueda', 'buscador__icono-busqueda');

            // input real
            const inputProducto = document.createElement('INPUT');
            inputProducto.type = 'text';
            inputProducto.name = 'producto-nombre';
            inputProducto.classList.add('buscador__campo', 'buscador__campo-focus');
            inputProducto.placeholder = 'Nombre del producto';

            if (inputProductoFalso.value !== '') {
                inputProducto.value = inputProductoFalso.value;
            }

            const contenedorBusqueda = document.createElement('DIV');
            contenedorBusqueda.classList.add('buscador__opciones-contenedor--busqueda')

            contenedorBusqueda.appendChild(iconoBuscador);
            contenedorBusqueda.appendChild(inputProducto);
            contenedorOpciones.appendChild(contenedorBusqueda);

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

                filtrarResultado(e, lista, contenedorOpciones);
            });

            contenedorInput.addEventListener('mouseleave', function () {

                inputProductoFalso.value = inputProducto.value;
                // eliminar html
                while (contenedorOpciones.firstChild) {
                    contenedorOpciones.removeChild(contenedorOpciones.firstChild);
                }
                contenedorOpciones.remove();

            });



        }


        async function filtrarResultado(e, lista, contenedorOpciones) {
            try {
                if (!flag) {
                    arrayCoincidencias = await findDB(e.target.value); // Almaceno la respuesta en memoria
                }

            } catch (error) {
                console.log(error);
            }
            if (flag) { // aqui puedo filtrar el array en memoria

                buscarCoincidenciasMemoria(e, lista, contenedorOpciones);
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

        function buscarCoincidenciasMemoria(e, lista, contenedorOpciones) {
            const busqueda = e.target.value;
            const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

            coincidenciasPantalla = arrayCoincidencias.filter(coincidencia => {
                if (coincidencia.nombre.toLowerCase().search(Regex) !== -1) {
                    return coincidencia;
                }
            });

            generarHTMLcoincidencia(lista, contenedorOpciones)
        }

        function generarHTMLcoincidencia(lista, contenedorOpciones) {

            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }

            let acu = 0; // Cantidad de coincidencias

            coincidenciasPantalla.forEach(coincidencia => {

                acu++;
                if (acu <= 6) {

                    const sugerenciaBusqueda = document.createElement('LI');
                    sugerenciaBusqueda.textContent = coincidencia.nombre;

                    sugerenciaBusqueda.addEventListener('click', function (e) {

                        buscarProducto(coincidencia.id);
                    });

                    lista.appendChild(sugerenciaBusqueda);
                    contenedorOpciones.classList.add('buscador__opciones-contenedor--activo');
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

                if (resultado.precio.desc_porc) {
                    // Producto en oferta
                    cardProducto.innerHTML = `
                    <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                    <div class=" producto__contenedor producto__contenedor--descuento ">
                        <h3 class="producto__card-nombre">${resultado.producto.nombre} - <span class="c-red">En Oferta</span></h3>
                        <div class="producto__contenedor-precio">
                            <p class="producto__card-precio producto__card-precio--oferta">$ ${resultado.producto.venta}<span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                        </div>
                        <div class="producto__card-info">
                            <p><span class=" font-bold">Descuento: </span>${resultado.precio.desc_porc} % </p>
                            <p><span class=" font-bold">Descuento finaliza en: </span>${resultado.precio.semanas_restantes} semanas</p>
                            <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                            <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                            <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                            <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                        </div>
        
                    </div>
                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
                </a>
                `;

                } else {

                    // No tiene descuento
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
                }

                inputProductoFalso.value = '';


            } catch (error) {
                console.log('El servidor no responde' + error);
            }
        }
    }

})();