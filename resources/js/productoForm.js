import Swal from 'sweetalert2';
import * as helpers from './helpers';

(function () {

    if (document.querySelector('#precio')) {

        const campoPersonalizado = document.querySelector('#ganancia');
        const campoSinIva = document.querySelector('#precio');
        const campoConIva = document.querySelector('#precio-iva');
        const btnVenta = document.querySelector('#btn-venta'); // calcular precio venta
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const campoVenta = document.querySelector('#precio-venta');
        const checkFraccion = document.querySelector('#check-fraccion'); // abre formulario secundario
        const contenedorOculto = document.querySelector('#producto-contenedor-oculto');
        let codigoFraccionado = document.querySelector('#codigo-fraccionado');
        const unidadFraccion = document.querySelector('#unidad-fraccion');
        const totalFraccionado = document.querySelector('#contenido-total');
        const gananciaFraccion = document.querySelector('#ganancia-fraccion');
        const precioFraccionado = document.querySelector('#precio-fraccionado');
        const btnFraccionado = document.querySelector('#btn-fraccionado'); // calcular precio fraccionado

        const radiobtns = document.querySelectorAll('input[type="radio"]');
        let radioChecked = document.querySelector('input[type="radio"]:checked');
        let precioVenta = 0;
        const click = true;

        const selectCat = document.querySelector('#categoria');
        const selectProv = document.querySelector('#provider');

        const btnDestroy = document.querySelector('#producto-destroy');
        const idHidden = document.querySelector('#producto-id');


        radiobtns.forEach(btn => {
            btn.addEventListener('click', (e) => {

                habilitarCampo(e);

                calcularGanancia();
                campoVenta.value = '';
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (campoConIva.value !== undefined) {
                if (campoSinIva.value !== '') {
                    campoConIva.value = Math.round(campoSinIva.value * 1.21);

                }

                calcularGanancia();
            }
        });

        campoSinIva.addEventListener('input', function () {

            precioFraccionado.value = 0;
            campoVenta.value = 0;
            calcularGanancia(click);
            campoConIva.value = Math.round(campoSinIva.value * 1.21);
        });

        campoConIva.addEventListener('input', function () {
            campoSinIva.value = Math.round(campoConIva.value / 1.21);

        });

        // Consultar precio venta
        btnVenta.addEventListener('click', function () {

            calcularGanancia(click);

        });

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

            if (selectCat.value !== null && selectProv.value !== null) { // Debe escoger categoria y provider primero

                radioChecked = document.querySelector('input[type="radio"]:checked');

                if (radioChecked.value === 'personalizada') {
                    // Calculo leyendo el formulario
                    precioVenta = (campoSinIva.value * 1.21) * campoPersonalizado.value;

                } else if (radioChecked.value === 'provider') {

                    // Consulta la DB
                    const provider_id = document.querySelector('#provider');
                    let ganancia = await consultarGanancia(radioChecked.value, provider_id.value);
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;

                } else if (radioChecked.value === 'categoria') {

                    const categoria_id = document.querySelector('#categoria');
                    let ganancia = await consultarGanancia(radioChecked.value, categoria_id.value);
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;

                }

                if (click) { // Solo cambio el "precio venta" si es presionado el btn de calcular
                    campoVenta.value = helpers.redondear(precioVenta);
                }

                if (checkFraccion) {

                    if (checkFraccion.checked === true) {
                        checkFraccion.checked = false;
                        deseleccionarFraccionado();

                    }
                }
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

            if (precioVenta) {
                // Calculo leyendo el formulario
                precioFraccionado.value = helpers.redondear((precioVenta / totalFraccionado.value) * gananciaFraccion.value);
            } else {
                console.log('Debes calcular el precio No Fraccionado primero');
            }
        }

        function deseleccionarFraccionado() {

            contenedorOculto.classList.add('producto-formulario__contenedor-oculto');
            contenedorOculto.classList.remove('producto-formulario__contenedor-visible');

            // Vaciar campos
            unidadFraccion.required = false;
            totalFraccionado.required = false;
            gananciaFraccion.required = false;

            precioFraccionado.value = '';
            unidadFraccion.value = null; // Cambio de '' a null
            totalFraccionado.value = null;
            gananciaFraccion.value = null;
            codigoFraccionado = null;
        }

        // Toma un id a eliminar, un tipo (fabricante, provider o fabricante) que será parte de la url hacia la API
        // y un array (opcional) en caso de utilizar vitualDOM
        // Contiene una llamada al método mostrarElementos(), este debe contener el scripting de los elementos HTML del paginador
        // Contiene una llamada a filtrarVirtualDOM(), es un helper
        // Contiene una llamada a destroy(), es un helper (id y tipo son pasados a destroy())

        btnDestroy.onclick = function () {
            console.log(idHidden.value);
            alertaDelete(idHidden.value, "producto", tokenCSRF);
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
                confirmButtonText: 'Si, muerte!',
                cancelButtonText: 'No, era una prueba!',
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
                            }, 900);
                        } else if (resultado.eliminar_doble) {

                            // Nueva alerta
                            ////// <<< Segunda alerta
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
                                confirmButtonText: 'Si, muerte!',
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
                            ////// <<< Fin segunda alerta


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

        //////////
    }
})();


