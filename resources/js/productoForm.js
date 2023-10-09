import Swal from 'sweetalert2';
import * as helpers from './helpers';

(function () {

    if (document.querySelector('#precio')) {

        const campoPersonalizado = document.querySelector('#ganancia');
        const campoSinIva = document.querySelector('#precio');
        const campoConIva = document.querySelector('#precio-iva');
        const btnVenta = document.querySelector('#btn-venta'); // calcular precio venta
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const campoNombre = document.querySelector('#name');
        const campoVenta = document.querySelector('#precio-venta');
        const checkFraccion = document.querySelector('#check-fraccion'); // abre formulario secundario
        const contenedorOculto = document.querySelector('#producto-contenedor-oculto');
        let codigoFraccionado = document.querySelector('#codigo-fraccionado');
        const unidadFraccion = document.querySelector('#unidad-fraccion');
        const totalFraccionado = document.querySelector('#contenido-total');
        const gananciaFraccion = document.querySelector('#ganancia-fraccion');
        const gananciaNumero = document.querySelector('#ganancia-numero');
        const precioFraccionado = document.querySelector('#precio-fraccionado');
        const btnFraccionado = document.querySelector('#btn-fraccionado'); // calcular precio fraccionado
        const btnSubmit = document.querySelector('input[type="submit"]');

        const radiobtns = document.querySelectorAll('input[type="radio"]');
        let radioChecked = document.querySelector('input[type="radio"]:checked');

        const selectCat = document.querySelector('#categoria');
        const selectProv = document.querySelector('#provider');

        const btnDestroy = document.querySelector('#producto-destroy');
        const idHidden = document.querySelector('#producto-id');

        // Inputs hidden en caso de ser un "pendiente"
        const descHidden = document.querySelector('input[name="desc_porc"]');
        const durHidden = document.querySelector('input[name="desc_duracion"]');
        const stockHidden = document.querySelector('input[name="stock"]');

        let precioVenta = '';
        const click = true;

        btnSubmit.addEventListener('click', () => {

            if (radioChecked.value === 'personalizada') {

                gananciaNumero.value = campoPersonalizado.value;
            } else {
                gananciaNumero.value = '';
            }

        });

        radiobtns.forEach(btn => {
            btn.addEventListener('click', (e) => {

                habilitarCampo(e);
                calcularGanancia();
                campoVenta.textContent = '$ 0';
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (campoConIva.value !== undefined) {
                if (campoSinIva.value !== '') {
                    campoConIva.value = Math.round(campoSinIva.value * 1.21);

                    calcularGanancia();
                }
            }

            (async () => {
                const resultado = await contadorPendiente();

                mensajePendiente(resultado);
            })();
        });

        campoSinIva.addEventListener('input', function () {

            if (campoSinIva.value.length >= 10) {
                campoVenta.textContent = "#Error";
                return;
            }

            precioFraccionado.textContent = '$ 0';
            campoVenta.textContent = '$ 0';
            calcularGanancia(click);

            // Redondea los decimas y luego convierte el string a float
            campoConIva.value = parseFloat((campoSinIva.value * 1.21).toFixed(2));

        });

        campoConIva.addEventListener('input', function () {

            if (campoConIva.value.length >= 10) {
                campoVenta.textContent = "#Error";
                return;
            }

            calcularGanancia(click);

            // Redondea los decimas y luego convierte el string a float
            campoSinIva.value = parseFloat((campoConIva.value / 1.21).toFixed(2));

        });

        // Consultar precio venta
        btnVenta.addEventListener('click', function () {

            calcularGanancia(click);

        });
        function mensajePendiente(contador) {
            const contenedor = document.querySelector('#contenedor-pendientes');
            while (contenedor.firstChild) {
                contenedor.removeChild(contenedor.firstChild);
            }
            if (contador > 0) {
                const mensaje = document.createElement('P');
                mensaje.classList.add('mensaje__info', 'mensaje__pendientes');
                mensaje.textContent = `Productos Pendientes: ${contador}`;

                contenedor.appendChild(mensaje);
                // Carga datos de un penidente en el formulario
                mensaje.addEventListener('click', cargarPendiente);

            }
        }

        async function cargarPendiente() {

            // Consultar DB por el penidente mas antiguo
            const pendiente = await consultarPenidente();

            if (pendiente.desc_porc) {
                descHidden.value = pendiente.desc_porc;
                durHidden.value = pendiente.desc_duracion;
            }
            if (pendiente.stock) {
                stockHidden.value = pendiente.stock;
            }

            // Completar los campos con este pendiente
            campoSinIva.value = pendiente.precio;
            campoNombre.value = pendiente.nombre;
            campoConIva.value = helpers.redondear(pendiente.precio * 1.21);

            // Eliminar pendiente, alerta "pendiente eliminado/cargado"
            const resultado = await deletePendiente(pendiente.id);

            // Recargar alerta, eliminar si no hay mas pendientes
            if (resultado) {
                const respuesta = await contadorPendiente();
                mensajePendiente(respuesta);
            }

        }
        async function contadorPendiente() {
            try {
                const url = '/api/pendientes/count';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }
        }
        async function consultarPenidente() {
            try {
                const url = '/api/pendientes/index';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }
        }
        async function deletePendiente(id) {

            try {
                const datos = new FormData();
                datos.append('id', id)

                const url = '/api/pendientes/destroy';

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                const resultado = await respuesta.json();
                return resultado;

            } catch (error) {
                console.log('El servidor no responde');
            }
        }

        // Habilitar / Deshabilitar campo opcional
        function habilitarCampo(e) {

            if (e.target.value === 'personalizada' && campoPersonalizado.readOnly === true) {

                campoPersonalizado.readOnly = false;
                campoPersonalizado.classList.remove('formulario__campo--no-activo');
            } else if (e.target.value !== 'personalizada') {

                campoPersonalizado.readOnly = true;
                campoPersonalizado.classList.add('formulario__campo--no-activo');
                campoPersonalizado.value = '';
            }
        }

        async function calcularGanancia(click) {

            if (selectCat.value !== '' && selectProv.value !== '') { // Debe escoger categoria y provider primero

                radioChecked = document.querySelector('input[type="radio"]:checked');

                if (radioChecked.value === 'personalizada') {
                    // Calculo leyendo el formulario
                    precioVenta = (campoSinIva.value * 1.21) * campoPersonalizado.value;
                    gananciaNumero.value = campoPersonalizado.value;

                } else if (radioChecked.value === 'provider') {

                    // Consulta la DB
                    const provider_id = document.querySelector('#provider');
                    let ganancia = await consultarGanancia(radioChecked.value, provider_id.value);
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;
                    gananciaNumero.value = '';

                } else if (radioChecked.value === 'categoria') {

                    const categoria_id = document.querySelector('#categoria');
                    let ganancia = await consultarGanancia(radioChecked.value, categoria_id.value);
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;
                    gananciaNumero.value = '';

                }

                if (click) { // Solo cambio el "precio venta" si es presionado el btn de calcular
                    campoVenta.textContent = "$ " + helpers.redondear(precioVenta);
                }

                if (checkFraccion) {

                    if (checkFraccion.checked === true) {
                        checkFraccion.checked = false;
                        deseleccionarFraccionado();

                    }
                }

                // Evita que se desborde el Precio de Venta
                if (campoVenta.textContent.length > 10) {
                    campoVenta.classList.add('font-sm');
                } else {
                    campoVenta.classList.remove('font-sm');
                }
                if (campoVenta.textContent.length > 20) {
                    campoVenta.classList.remove('font-sm');
                    campoVenta.textContent = "#Error";
                }

            } else {
                Swal.fire(
                    'Oops!',
                    'Aún no has escogido la categoria y el proveedor.',
                    'info'
                );
            }
        }

        async function consultarGanancia(seleccion, id) {

            try {
                const datos = new FormData();
                datos.append('ganancia', seleccion);
                datos.append('id', id)

                const url = '/api/calculo/ganancia';

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                let resultado = await respuesta.json();
                campoPersonalizado.value = resultado;
                return resultado;

            } catch (error) {
                console.log('El servidor no responde');
            }
        }

        btnFraccionado.addEventListener('click', calcularGananciaFraccionado);

        if (checkFraccion) {
            checkFraccion.addEventListener('click', function () {

                if (checkFraccion.checked === true) {

                    // Seleccionado
                    contenedorOculto.classList.add('producto-formulario__contenedor-visible');
                    contenedorOculto.classList.remove('producto-formulario__contenedor-oculto');

                    // Consultar DB para obtener código
                    if (codigoFraccionado.value === '') {
                        (async () => {
                            const codigo = await generarCodigo();
                            codigoFraccionado.value = codigo.toUpperCase(); // Nuevo código
                        })();
                    }

                    // Añadir required al HTML
                    unidadFraccion.required = true;
                    totalFraccionado.required = true;
                    gananciaFraccion.required = true;

                    // Generar código
                    async function generarCodigo() {

                        try {
                            const url = '/api/codigo-unico';
                            const respuesta = await fetch(url);
                            const resultado = await respuesta.json();

                            return resultado;

                        } catch (error) {
                            console.log(error);
                        }
                    }
                } else {
                    // Deseleccionado
                    deseleccionarFraccionado();
                }
            });
        }

        async function calcularGananciaFraccionado() {

            if (precioVenta && gananciaFraccion.value !== '' && totalFraccionado.value) {
                // Calculo leyendo el formulario
                precioFraccionado.textContent = "$ " + helpers.redondear((precioVenta / totalFraccionado.value) * gananciaFraccion.value);

                if (precioFraccionado.textContent.length > 10) {
                    precioFraccionado.textContent = "#Error";
                }
            } else {
                Swal.fire(
                    'Oops!',
                    'Debes calcular el precio no fraccionado, ¡también debes indicar la ganancia del fraccionado y la cantidad de unidades!',
                    'info'
                );
            }
        }

        function deseleccionarFraccionado() {

            contenedorOculto.classList.add('producto-formulario__contenedor-oculto');
            contenedorOculto.classList.remove('producto-formulario__contenedor-visible');

            // Vaciar campos
            unidadFraccion.required = false;
            totalFraccionado.required = false;
            gananciaFraccion.required = false;

            precioFraccionado.textContent = '$ 0';
            unidadFraccion.value = null; // Cambio de '' a null
            totalFraccionado.value = null;
            gananciaFraccion.value = null;
            codigoFraccionado.value = '';
        }

        // Toma un id a eliminar, un tipo (fabricante, provider o fabricante) que será parte de la url hacia la API
        // y un array (opcional) en caso de utilizar vitualDOM
        // Contiene una llamada al método mostrarElementos(), este debe contener el scripting de los elementos HTML del paginador
        // Contiene una llamada a filtrarVirtualDOM(), es un helper
        // Contiene una llamada a destroy(), es un helper (id y tipo son pasados a destroy())

        if (btnDestroy) {
            btnDestroy.onclick = function () {
                console.log(idHidden.value);
                alertaDelete(idHidden.value, "producto", tokenCSRF);
            }
        }

        async function alertaDelete(id, tipo, token = null) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Estas seguro?',
                text: "No hay vuelta atras",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {
                        const resultado = await destroy(id, tipo, token);

                        if (resultado.eliminado) {
                            swalWithBootstrapButtons.fire(
                                'Eliminado/a',
                                'El producto ha sido destruido :(',
                                'success'
                            );
                            setTimeout(() => {
                                window.location.href = "/"; // redirijo al usuario
                            }, 1300);
                        } else if (resultado.eliminar_doble) {

                            // Nueva alerta
                            const swalWithBootstrapButtons2 = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                            swalWithBootstrapButtons2.fire({
                                title: 'Dos productos serán eliminados',
                                text: "No hay vuelta atras!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Eliminar',
                                cancelButtonText: 'No, no... mejor no.',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    (async function () {
                                        const resultado = await destroy(id, tipo, token, true);

                                        if (resultado.eliminado) {
                                            swalWithBootstrapButtons2.fire(
                                                'Eliminado/a',
                                                'Ambos productos han sido eliminados :(',
                                                'success'
                                            );
                                            setTimeout(() => {
                                                window.location.href = "/"; // redirijo al usuario
                                            }, 900);
                                        }
                                    })();
                                } else if (
                                    result.dismiss === Swal.DismissReason.cancel
                                ) {
                                    swalWithBootstrapButtons2.fire(
                                        'Cancelado',
                                        'No se han hecho cambios',
                                        'error'
                                    );
                                }
                            });

                        } else {
                            swalWithBootstrapButtons.fire(
                                'No puede ser eliminado',
                                'Ocurrio un error',
                                'error'
                            );
                        }
                    })();
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                        'No se han hecho cambios',
                        'error'
                    );
                }
            });
        }

        // Toma un id a eliminar y un tipo, este puede ser "fabricante, provider o fabricante". El tipo es parte de la URL hacia la API
        // tokenCSRF debe estar definido como variable global dentro del archivo que importa estas funciones
        async function destroy(id, tipo, token, confirm = false) {

            try {
                const datos = new FormData();
                datos.append('id', id);
                datos.append('confirm', confirm);

                const url = '/api/' + tipo + 's/destroy';
                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: datos
                });

                let resultado = await respuesta.json();
                return resultado;

            } catch (error) {
                console.log('El servidor no responde');
            }
        }
    }
})();


