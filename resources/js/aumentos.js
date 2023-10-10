import Swal from 'sweetalert2';

(function () {

    if (document.querySelector('#btn-aumentos-cat')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const btnCatPorc = document.querySelector('#btn-aumentos-cat');
        const categoria = document.querySelector('#aumentos-categoria');
        const categoriaPorc = document.querySelector('#aumentos-porc-cat');

        const btnProPorc = document.querySelector('#btn-aumentos-pro');
        const provider = document.querySelector('#aumentos-provider');
        const providerPorc = document.querySelector('#aumentos-porc-pro');

        const btnFabPorc = document.querySelector('#btn-aumentos-fab');
        const fabricante = document.querySelector('#aumentos-fabricantes');
        const fabricantePorc = document.querySelector('#aumentos-porc-fab');


        btnCatPorc.addEventListener('click', function () {

            alertaAumento('categoria', categoria.value, categoriaPorc.value);
            categoria.value = '';
            categoriaPorc.value = '';

        });

        btnProPorc.addEventListener('click', function () {

            alertaAumento('provider', provider.value, providerPorc.value);
            provider.value = '';
            providerPorc.value = '';

        });

        btnFabPorc.addEventListener('click', function () {

            alertaAumento('fabricante', fabricante.value, fabricantePorc.value);
            fabricante.value = '';
            fabricantePorc.value = '';

        });

        function alertaAumento(tipo, valor, porcentaje) {

            if (porcentaje > 500 || porcentaje < 1) {

                Swal.fire(
                    'Oops!',
                    'El porcentaje debe ser entre 1% - 500%',
                    'info'
                );

                return;
            }

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Estas seguro?',
                text: "No hay vuelta atras",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, aumentar ' + porcentaje + "%",
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {
                        try {

                            const resultado = await aumento(tipo, valor, porcentaje);

                            if (resultado.errors !== null) {
                                await validaciones(resultado);

                            } else {

                                swalWithBootstrapButtons.fire(
                                    'Precios actualizados',
                                    resultado.afectados.toString() + ' precios han sido actualizados.',
                                    'success'
                                );
                            }
                        } catch (error) {
                            console.log(error);
                        }
                    })();
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                        'No se han hecho cambios',
                        'error'
                    )
                }
            });
        }

        async function aumento(tipo, id, porcentaje) {

            try {
                const datos = new FormData();
                datos.append(tipo + '_id', id);
                datos.append('porcentaje', porcentaje)

                const url = '/api/aumentos/' + tipo;

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                let resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log('El servidor no responde');
            }
        }

        async function validaciones(resultado) {

            // Evalua el array "errors" dentro del resultado, identificando el campo y el mensaje
            for (let campo in resultado.errors) {
                if (resultado.errors.hasOwnProperty(campo)) {
                    let mensajesDeError = resultado.errors[campo];

                    for (let i = 0; i < mensajesDeError.length; i++) {

                        // Mensaje de error, recibe el campo, el mensaje y el tipo (categoria, provider o fabricante)
                        mensajeError(campo, mensajesDeError[i], resultado.tipo);

                    }
                }
            }
        }

        function mensajeError(campo, mensaje, tipo) { // campo incluye "_id" como "categoria_id" (el nombre puesto en la API)

            // Eliminar errores anteriores
            const errores = document.querySelectorAll(".alerta__error");
            errores.forEach(element => {
                element.remove();
            });
            const bordeErrores = document.querySelectorAll(".borde__error");
            bordeErrores.forEach(element => {
                element.classList.remove("borde__error");
            });

            campo = campo.replace("_id", "");
            const mensajeParrafo = document.createElement('P');
            mensajeParrafo.classList.add('alerta__error');
            mensajeParrafo.textContent = mensaje;

            let padre = '';

            if (tipo === "categoria") {
                if (campo === "porcentaje") {

                    padre = categoriaPorc.parentNode;
                    categoriaPorc.classList.add('borde__error');

                } else {

                    categoria.classList.add('borde__error');
                    padre = categoria.parentNode;

                }

            } else if (tipo === "fabricante") {
                if (campo === "porcentaje") {

                    padre = fabricantePorc.parentNode;
                } else {

                    padre = fabricante.parentNode;
                }
            } else if (tipo === "provider") {
                if (campo === "porcentaje") {

                    padre = providerPorc.parentNode;
                } else {

                    padre = provider.parentNode;
                }
            }

            padre.appendChild(mensajeParrafo);
        }
    }
})();
