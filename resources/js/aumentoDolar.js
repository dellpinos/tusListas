import * as helpers from './helpers';
import Swal from 'sweetalert2';

(function () {

    if (document.querySelector('#btn-dolar')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const dolarInput = document.querySelector('#aumento-dolar');
        const btnDolar = document.querySelector('#btn-dolar');
        const contRegistros = document.querySelector('#aumento-dolar-registros');
        const contPaginacion = document.querySelector('#table-paginacion');
        const table = document.querySelector('.table');
        const mensajeNoResult = document.querySelector('#desactualizados-mensaje');
        const mensajeInfo = document.querySelector('#desactualizados-info');
        const btnDolarAct = document.querySelector('#btn-dolar-actualizar');

        // Virtual DOM
        let productosArray = [];
        let preciosArray = [];

        // pagina actual
        let page = 1;

        // paginacion
        let paginacion = '';

        // valor a comparar
        let valor = 0;

        // mensaje Info
        mensajeInfo.textContent = "Los 5 productos con el dolar mas bajo o desactualizado";

        // Obtener elementos
        listadoDesactualizados();

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

                mensajeInfo.classList.remove('display-none');
                btnDolarAct.classList.remove('display-none');
                mensajeInfo.textContent = "Productos con un valor dolar inferior a U$S " + valor;

                productosArray = resultado.productos;
                preciosArray = resultado.precios;
                paginacion = resultado.paginacion;

                // Generar elementos
                mostrarElementos();

                btnDolarAct.addEventListener('click', () => {
                    console.log(valor);
                    // Alerta sweet Alert
                    actualizarPrecios();

                    // POST a la DB

                    // Alerta por cambio realizado
                });


            } catch (error) {
                console.log(error);
            }
        }

        // dentro del try/catch
        // envio al servidor el valor para que retorne la cantidad de registros que seran afectados
        // Alerta para confirmar operacion con el numero de registros afectados
        // Si confirma
        // Toma el valor ingresado por el usuario
        // Envia este valor y el servidor debe consultar todos los precios con el dolar por debajo de este valor
        // En cada Precio divide el valor (nuevo) por el dolar almacenado (porcentaje de aumento)
        // multiplica este porcentaje por el precio de costo sin iva
        // el servidor retorna la cantidad de resultados afectados
        // se dispara la alerta de exito



        async function actualizarPrecios() {
            try {

                const url = '/api/aumentos/dolar-count';
                const datos = new FormData();
                datos.append('valor', valor);

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });
        
                const resultado = await respuesta.json();

                await alertaUpdate(valor, resultado);


            } catch (error) {
                console.log('El servidor no responde' + error);
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

            mensajeInfo.classList.add('display-none');
            table.classList.add('display-none');
            btnDolarAct.classList.add('display-none');

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
                        <td class="table__td"><a class="table__accion table__accion--editar" href="/producto/producto-show/${producto.id}">Editar</a></td>
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

        async function alertaUpdate(valor, afectados) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Estas seguro?',
                text: "No hay vuelta atras, seran afectados " + afectados + " precios.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si!',
                cancelButtonText: 'No, era una prueba!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {
                        const resultado = await update(valor, afectados);

                        if (resultado) {
                            swalWithBootstrapButtons.fire(
                                'Precios actualizados',
                                afectados + " precios han sido actualizados",
                                'success'
                            );
                            // Recargar en pantalla y reiniciar VirtualDOM

                            reiniciarPagina();

                        } else {

                            swalWithBootstrapButtons.fire(
                                'Ups!',
                                'Surgió un error, no se realizaron cambios',
                                'error'
                            );
                        }
                    })();
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                        'No se realizaron cambios',
                        'error'
                    );
                }
            });
        }

        async function update(valor, afectados) {

            const url = '/api/aumentos/dolar-update';
            const datos = new FormData();

            datos.append('valor', valor);
            datos.append('afectados', afectados);

            const respuesta = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenCSRF
                },
                body: datos
            });
    
            const resultado = await respuesta.json();
            return resultado;
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
        function reiniciarPagina() {

            dolarInput.value = '';
            valor = 0;
            preciosArray = [];
            productosArray = [];
            limpiarProductos();
            mensajeInfo.classList.remove('display-none');
            mensajeInfo.textContent = "Los 5 productos con el dolar mas bajo o desactualizado";

            btnDolarAct.classList.add('display-none');
            listadoDesactualizados();
            
        }
    }
})();
