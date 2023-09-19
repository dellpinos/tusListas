
// Consulto todos los productos y precios - productosDolar
// Asigno los elementos consultados en los array globales
// Recorro los array globales generando el código con scripting
// Al utilizar scripting tengo acceso a los eventos como el click sobre los botones
import * as helpers from './helpers';

(function () {

    if (document.querySelector('#btn-dolar')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const dolarInput = document.querySelector('#aumento-dolar');
        const btnDolar = document.querySelector('#btn-dolar');
        const contRegistros = document.querySelector('#aumento-dolar-registros'); // El DIV del paginador
        const contPaginacion = document.querySelector('#table-paginacion');
        const table = document.querySelector('.table');
        const mensajeNoResult = document.querySelector('#desactualizados-mensaje');
        const mensajeInfo = document.querySelector('#desactualizados-info');

        // Virtual DOM
        let productosArray = [];
        let preciosArray = [];

        // pagina actual
        let page = 1;

        // paginacion
        let paginacion = '';

        // valor a comparar
        let valor = 0;

        // Obtener elementos
        listadoDesactualizados();

        // mensaje.innerHTML = `<p class="mensaje__info--my">
        // Los 5 productos con el dolar mas bajo.
        // </p>`



        btnDolar.addEventListener('click', function () {
            // Busqueda con el boton
            valor = dolarInput.value;

            // limpiar virtual DOM
            productosArray = [];
            preciosArray = [];
            table.classList.remove('display-none');

            // Consultar DB
            paginadorDesactualizados();

        });

        async function paginadorDesactualizados() {
            try {
                const url = '/api/aumentos/dolar-busqueda';

                const datos = new FormData();
                datos.append('valor', valor);
                datos.append('page', page);

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                const resultado = await respuesta.json();

                if (!resultado.productos || !resultado.precios) {

                    sinResultados();
                    return;
                }

                mensajeInfo.textContent = "Productos con un valor dolar inferior a U$S " + valor;

                productosArray = resultado.productos;
                preciosArray = resultado.precios;
                paginacion = resultado.paginacion;

                // Generar elementos
                mostrarElementos();

                // Generar boton actualizar

                // <a class="formulario__boton" id="btn-dolar-actualizar">Actualizar todos</a>

            } catch (error) {
                console.log(error);
            }
        }

        // Consultar todos los elementos
        async function listadoDesactualizados() {
            try {

                const url = '/api/aumentos/dolar-listado';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                productosArray = resultado.productos; // array de productos
                preciosArray = resultado.precios; // array de precios

                

                // <<< Mostrar Elementos
                mostrarElementos();

            } catch (error) {
                console.log('No carga el listado');
            }
        }

        function sinResultados() {
            limpiarProductos();

            mensajeNoResult.innerHTML = `<p class="mensaje__info--my">
            No hay productos desactualizados
        </p>`

            table.classList.add('display-none');

        }

        /// Recorre el Virtual DOM
        function mostrarElementos() {

            // <<<< Limpiar elementos
            limpiarProductos();

            productosArray.forEach(producto => { // Cada producto
                preciosArray.forEach(precio => { // Cada precio
                    if (precio.id === producto.precio_id) {

                        producto.venta = helpers.redondear(producto.venta);

                        // Formatear fecha (se obtiene tal cual esta almacenada en la DB)
                        const fechaObj = new Date(precio.updated_at);
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

                        if (producto.unidad_fraccion === null) {
                            producto.unidad_fraccion = '';
                        }

                        contRegistros.innerHTML += `                        
                        <tr class="table__tr">
                        <td class="table__td">${precio.dolar}</td>
                        <td class="table__td">${producto.codigo.toUpperCase()}</td>
                        <td class="table__td">${producto.nombre}</td>
                        <td class="table__td">$ ${precio.precio}</td>
                        <td class="table__td">$ ${producto.venta} ${producto.unidad_fraccion}</td>
                        <td class="table__td">${fechaFormateada}</td>
                        <td class="table__td"><a href="/producto/producto-show/${producto.id}">Ver</a></td>
                        </tr>
                    `;

                        if (paginacion !== '') {
                            contPaginacion.innerHTML = paginacion;

                            const enlaceNumero = document.querySelectorAll('[data-page]');
                            enlaceNumero.forEach(numero => {
                                numero.addEventListener('click', (e) => {
                                    console.log(e.target.dataset.page);
                                    // modificar page
                                    page = e.target.dataset.page;
                                    paginadorDesactualizados();
                                    return;
                                    // regenerar HTML
                                });
                            });

                            const enlaceBtn = document.querySelectorAll('[data-btn]');
                            enlaceBtn.forEach(boton => {
                                boton.addEventListener('click', (e) => {
                                    console.log(e.target.dataset.btn);
                                    if (e.target.dataset.btn === 'siguiente') {
                                        // modificar page ++
                                        // regenerar HTML
                                        page++;
                                        paginadorDesactualizados();
                                        return;

                                    } else {
                                        // modificar page --
                                        // regenerar HTML
                                        page--;
                                        paginadorDesactualizados();
                                        return;
                                    }
                                });
                            });
                        }
                    }
                }); // Fin cada precio
            }); // Fin cada producto
        }

        function limpiarProductos() {
            while (contRegistros.firstChild) {
                contRegistros.removeChild(contRegistros.firstChild);
            }
            while (contPaginacion.firstChild) {
                contPaginacion.removeChild(contPaginacion.firstChild);
            }
            while (mensajeNoResult.firstChild) {
                mensajeNoResult.removeChild(mensajeNoResult.firstChild);
            }


        }

    }

})();

// Ciclos combinados, un ciclo va a generar cada tabla para el paginador, se generan todos los titlulos y
// los slides del paginados
// Un segundo ciclo para completar esta tabla











// los slides son los registros del body de la tabla, no los titulos. Cuando el usuario presiona el boton de
// consulta a la DB se genera la tabla y el thead, luego se itera la respuesta para generar el tbody y la paginación
// del mismo


// La tabla no debe ser generada, ya esta creada en el HTML. Se elimina el mensaje "10 productos mas desactualizados"
// y se reemplazan los registros por la devolución de la base de datos.