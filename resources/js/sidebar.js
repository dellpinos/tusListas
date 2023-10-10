(function () {

    if (document.querySelector('.sidebar__nav')) {

        document.addEventListener('DOMContentLoaded', async () => {

            try {

                const iconoProducto = document.querySelector('#sidebar__new-prod');
                const resultado = await consultaPendientes();

                if (resultado > 0) {

                    const notif = document.createElement('I');
                    notif.classList.add('sidebar__alert', 'fa-solid', 'fa-circle-exclamation');
                    notif.id = 'sidebar__pendiente-alert';

                    iconoProducto.appendChild(notif);
                }

            } catch (error) {
                console.log(error);
            }

            async function consultaPendientes() {

                try {

                    const url = '/api/pendientes/count';

                    const respuesta = await fetch(url);
                    const resultado = await respuesta.json();

                    return resultado;
                } catch (error) {
                    console.log(error);
                }
            }
        });
    }
})();