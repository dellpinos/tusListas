import * as helpers from './helpers';

(function () {

    if (document.querySelector('#mercaderia-grid')) {


        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const grid = document.querySelector('#mercaderia-grid');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado

        let productoSelect = '';
        let precioSelect = '';

        document.addEventListener('DOMContentLoaded', generarForm);


        function generarForm() {

            const contenedorCheck = document.createElement('DIV');
            contenedorCheck.classList.add('formulario__contenedor-checkbox');

            const checkbox = document.createElement('INPUT');
            checkbox.type = 'checkbox';
            checkbox.classList.add('formulario__checkbox');

            contenedorCheck.appendChild(checkbox);

            const contenedorCodigo = document.createElement('DIV');
            contenedorCodigo.classList.add('relative');

            const codigo = document.createElement('INPUT');
            codigo.type = 'number';
            codigo.classList.add('formulario__campo');
            codigo.placeholder = "Código";

            contenedorCodigo.appendChild(codigo);

            const contenedorNombre = document.createElement('DIV');
            contenedorNombre.classList.add('relative');

            const nombre = document.createElement('INPUT');
            nombre.type = 'text';
            nombre.classList.add('formulario__campo');
            nombre.placeholder = "Nombre del producto";

            contenedorNombre.appendChild(nombre);

            const precio = document.createElement('INPUT');
            precio.type = 'number';
            precio.classList.add('formulario__campo');
            precio.placeholder = "Precio sin IVA";

            const descuento = document.createElement('INPUT');
            descuento.type = 'number';
            descuento.classList.add('formulario__campo');
            descuento.placeholder = "% Descuento";

            const semanas = document.createElement('SELECT');
            semanas.classList.add('formulario__campo');

            for (let i = 0; i <= 8; i++) {

                const option = document.createElement('OPTION');
                option.value = i;
                option.textContent = i;

                if (i === 0) {
                    option.selected = true;
                }
                semanas.appendChild(option);
            }

            const btnGuardar = document.createElement('BUTTON');
            btnGuardar.classList.add('boton');
            btnGuardar.textContent = "Guardar"

            grid.appendChild(contenedorCheck);
            grid.appendChild(contenedorCodigo);
            grid.appendChild(contenedorNombre);
            grid.appendChild(precio);
            grid.appendChild(descuento);
            grid.appendChild(semanas);
            grid.appendChild(btnGuardar);

            nombre.addEventListener('click', (e) => {

                generarHTML(e, codigo);

                console.log(productoSelect, precioSelect);

                /// Puedo enviar un objeto con todos los campos a completar
            });



        }

        //////////
        function generarHTML(e) {


            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor');

            // input real
            const inputProducto = document.createElement('INPUT');
            inputProducto.type = 'text';
            inputProducto.name = 'producto-nombre';
            inputProducto.classList.add('buscador__campo', 'buscador__campo-focus');
            inputProducto.placeholder = 'Nombre del Producto';

            if (e.target.value !== '') {
                inputProducto.value = e.target.value;
            }

            contenedorOpciones.appendChild(inputProducto);

            // listado de coincidencias
            const lista = document.createElement('UL');

            contenedorOpciones.appendChild(lista);


            e.target.parentNode.appendChild(contenedorOpciones);

            inputProducto.focus(); // cursor sobre el input

            // realizar busqueda
            inputProducto.addEventListener('input', function (element) {

                if (flag === 1 && e.target.value.length < 3) { // Reinicio el flag para volver a realizar la busqueda
                    flag = 0;
                }

                filtrarResultado(element, lista);
            });

            e.target.parentNode.addEventListener('mouseleave', function () {

                e.target.value = inputProducto.value;
                // eliminar html
                while (contenedorOpciones.firstChild) {
                    contenedorOpciones.removeChild(contenedorOpciones.firstChild);
                }
                contenedorOpciones.remove();

            });


        }


        /// <<<< Separar

        async function filtrarResultado(element, lista) {
            try {
                if (!flag) {
                    arrayCoincidencias = await findDB(element.target.value); // Almaceno la respuesta en memoria
                }

            } catch (error) {
                console.log(error);
            }
            if (flag) { // aqui puedo filtrar el array en memoria

                buscarCoincidenciasMemoria(element, lista);
            }
        }

        /// <<< Separar

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

        //// <<< Separar

        function buscarCoincidenciasMemoria(element, lista) {
            const busqueda = element.target.value;
            const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

            coincidenciasPantalla = arrayCoincidencias.filter(coincidencia => {
                if (coincidencia.nombre.toLowerCase().search(Regex) !== -1) {
                    return coincidencia;
                }
            });

            generarHTMLcoincidencia(lista)
        }


        //// <<< Separar

        function generarHTMLcoincidencia(lista) {

            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }

            let acu = 0; // Cantidad de coincidencias

            coincidenciasPantalla.forEach(coincidencia => {

                acu++;
                if (acu <= 4) {

                    const sugerenciaBusqueda = document.createElement('LI');
                    sugerenciaBusqueda.textContent = coincidencia.nombre;
                    lista.appendChild(sugerenciaBusqueda);

                    sugerenciaBusqueda.addEventListener('click', function (e) {

                        let resultado = buscarProducto(coincidencia.id);
                        return resultado;
                    });
                }
            });
        }

        //// <<< Separar

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

                return resultado;

                // precioSelect = resultado.precio;
                // productoSelect = resultado.producto;

                // console.log(productoSelect, precioSelect);



                console.log(resultado.producto.codigo + " <<< codigo resultado");



                // inputProductoFalso.value = '';
                // inputCodigo.value = '';

            } catch (error) {
                console.log('El servidor no responde');
            }
        }


        /////////






    }
})();