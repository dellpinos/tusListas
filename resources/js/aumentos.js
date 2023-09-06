import Swal from 'sweetalert2';

(function () {

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
            cancelButtonText: 'No, era una prueba!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                (async function () {
                    const resultado = await aumento(tipo, valor, porcentaje);

                    swalWithBootstrapButtons.fire(
                        'Precios actualizados',
                        resultado.toString() + ' precios han sido actualizados.',
                        'success'
                    )
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


})();