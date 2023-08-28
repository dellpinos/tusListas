(function () {

    const contenedorInput = document.querySelector('#contenedor-input');
    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inputProductoFalso = document.querySelector('#producto-nombre-falso');
    let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
    let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
    let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado


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
        inputProducto.placeholder = 'Pipeta power, Pecera 60x20, Collar Cuero, etc';

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

            // eliminar anterior

            // hacer consulta

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

                let cardProducto = document.querySelector('#card-producto');


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

                cardProducto.innerHTML = `
                <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                <div class=" producto__contenedor ">
                    <p><span class=" font-bold">Código: </span>${resultado.producto.codigo}</p>
                    <p><span class=" font-bold">Producto: </span>${resultado.producto.nombre}</p>
                    <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                    <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                    <p><span class=" font-bold">Precio venta: $ </span>${resultado.producto.venta}</p>
                    <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                
                    </div>
                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
            </a>
                `;


            } catch (error) {
                console.log('El servidor no responde');
            }


        }
    }





    // Escojo/recorto los primeros 5 registros del resultado e itero el array para crear cada <li>

    // utilizo el método slice(0, 5) para obtener los primeros 5 elementos del array e imprimirlos en pantalla

    // realizar busqueda dentro de las opciones de la db

    // resolver el scroll

    // añadir hover a los <li>
    // nagevar con el teclado?

    // Cada <li> debe ser seleccionable y esto realiza la busqueda en la base de datos y renderiza la
    // información del producto debajo de los buscadores, lo mismo tengo que hacer para la busqueda por codigo





})();