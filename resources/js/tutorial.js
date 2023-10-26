import Swal from 'sweetalert2';

(async function () {

    if (document.querySelector('.sidebar')) {


        console.log(window.location.pathname);

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Consultar
        const respuesta = await consultar();

        if (!respuesta && window.location.pathname === '/') {
            // Iniciar tutorial

            Swal.fire({
                title: '¡ Bienvenido a TusListas !',
                text: "Este es un pequeño tutorial para guiarte en tus primeros pasos",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Nada de tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Siguiente mensaje, alerta en AGENDA
                    crearIconoTutorial('agenda');

                    Swal.fire({

                        title: 'Primeros pasos',
                        text: 'Deberias comenzar por personalizar tu AGENDA, puedes encontrarla en la barra de herramientas',
                        icon: 'info',
                        showCancelButton: false,

                    });
                } else {
                    // Desactivar tutorial

                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo desde tu perfil',
                        'success'
                    );
                }
            });

        } // Cierre Primeros Pasos en "/"

        if (!respuesta && window.location.pathname.includes('/agenda')) {

            // Mensaje Agenda
            Swal.fire({
                title: 'Esta es tu agenda',
                text: "Aqui puedes almacenar tus Proveedores, las Categorias de tus productos y sus Fabricantes. En cada Categoria y Proveedor puedes indicar el indice de Ganancia que deseas aplicar a sus productos. Esto es muy útil para clasificar tus productos y posteriormente para hacer Aumentos Generales.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Nada de tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros pasos',
                        text: 'Deberias continuar creando tu primer Categoria, un Proveedor y un Fabricante. Piensa en cualquier producto de tu inventario: A que Categoria pertenece? Quien te lo Provee? Quien lo fabrica? También puedes utilizar nombres genericos como "Otros" y una ganancia de 1 (sin ganancia), pero será mas dificil clasificarlos en el futuro.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        // Siguiente mensaje, alerta en PRODUCTO
                        crearIconoTutorial('new-prod');

                        Swal.fire({

                            title: 'Primeros pasos',
                            text: 'Cuando hayas creado almenos una Categoria, un Proveedor y un Fabricante deberias crear tu primero PRODUCTO! Puedes encontrarlo en la barra de herramientas.',
                            icon: 'info',
                            showCancelButton: false,

                        });
                    });

                } else {

                    // Desactivar tutorial
                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo desde tu perfil',
                        'success'
                    );
                }
            });

        } // Cierre Primeros Pasos en "/agenda"

        if (!respuesta && window.location.pathname.includes('/producto')) {




            // Mensaje Agenda
            Swal.fire({
                title: 'Aquí puedes crear nuevos productos',
                text: "Solo debes darle un nombre y un precio, también puedes indicar la cotización del Dólar Hoy (prometo recordarlo en futuros productos).",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Nada de tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros pasos',
                        text: 'Tu producto ya tiene asignado un código y puedes escoger que tipo de Ganancia deseas aplicar para calcular el precio de venta. Recuerda que el IVA se calcula como 21%.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        // Siguiente mensaje, alerta en PRODUCTO
                        crearIconoTutorial('buscador');

                        Swal.fire({

                            title: 'Primeros pasos',
                            text: 'Cuando hayas creado tu primer producto estará disponible en el Buscador. Ya puedes organizar tu Agenda y añadir todos los productos que quieras, estarán disponibles en el buscador.',
                            icon: 'info',
                            showCancelButton: false,

                        }).then(() => {

                            Swal.fire({

                                title: 'Todo tiene un final',
                                text: 'Aqui finaliza el tutorial Primeros Pasos pero TusListas puede hacer mucho más, puedes buscar mas información en Ayuda.',
                                icon: 'info',
                                showCancelButton: false,
                            });

                        });

                        // Desactivar tutorial
                        activarDesactivar(1, tokenCSRF);
                    });

                } else {

                    // Desactivar tutorial
                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo desde tu perfil',
                        'success'
                    );
                }
            });






        }




        function crearIconoTutorial(elemento) {

            const icono = document.querySelector('#sidebar-' + elemento);

            const notif = document.createElement('I');
            notif.classList.add('sidebar__alert-tutorial', 'fa-solid', 'fa-circle-exclamation');
            notif.id = 'sidebar-tutorial-alert';

            icono.appendChild(notif);



        }



        // Modifica la elección del usuario (0 ver tutoriales - 1 no ver tutoriales)
        async function activarDesactivar(modificar, token) {

            try {
                const url = '/api/tutorial/modificar';
                const datos = new FormData();

                datos.append('modificar', modificar);

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: datos
                });
                const resultado = await respuesta.json();

                console.log(resultado);

                return resultado;

            } catch (error) {
                console.log(error);
            }
        }



        // Consulta si el usuario desea ver tutorial
        async function consultar() {

            try {
                const url = '/api/tutorial/consulta';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }
        }





    }


})();