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

            coincidenciasPantalla.forEach(coincidencia => {

                const sugerenciaBusqueda = document.createElement('LI');
                sugerenciaBusqueda.textContent = coincidencia.nombre;

                lista.appendChild(sugerenciaBusqueda);

            });

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