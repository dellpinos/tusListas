import Swal from 'sweetalert2';

(async function () {

    if (document.querySelector('.sidebar')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Consultar
        const respuesta = await consultar();

        if (!respuesta.tutorial && respuesta.tutorial_lvl === 0 && window.location.pathname === '/') {
            // Iniciar tutorial

            Swal.fire({
                title: '¡ Bienvenido a TusListas !',
                text: "Este es un pequeño tutorial para guiarte en tus Primeros Pasos.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Nada de tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Siguiente mensaje, alerta en AGENDA
                    iconoTutorial('agenda');

                    Swal.fire({

                        title: 'Primeros Pasos',
                        text: 'Deberias comenzar por personalizar tu AGENDA, puedes encontrarla en la barra de herramientas.',
                        icon: 'info',
                        showCancelButton: false,

                    });

                    setLvl(1, tokenCSRF);
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

        if (!respuesta.tutorial && respuesta.tutorial_lvl === 1 && window.location.pathname.includes('/agenda')) {

            // Mensaje Agenda
            Swal.fire({
                title: 'Esta es tu agenda',
                text: "Aqui puedes almacenar tus Proveedores, las Categorias de tus productos y sus Fabricantes. En cada Categoria y Proveedor puedes indicar el indice de GANANCIA que deseas aplicar a sus productos. Esto es muy útil para clasificar tus productos y posteriormente para hacer Aumentos Generales.",
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
                        text: 'Deberias continuar creando tu primer CATEGORIA.',
                        icon: 'info',
                        showCancelButton: false,

                    });

                    iconoAgenda('categorias');

                    setLvl(2, tokenCSRF);

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

        } else if (!respuesta.tutorial && respuesta.tutorial_lvl === 3 && window.location.pathname.includes('/agenda')) {


            Swal.fire({

                title: 'Primeros Pasos',
                text: 'Deberias continuar creando tu primer FABRICANTE.',
                icon: 'info',
                showCancelButton: false,

            });

            iconoAgenda('fabricantes');

            setLvl(4, tokenCSRF);


        } else if (!respuesta.tutorial && respuesta.tutorial_lvl === 5 && window.location.pathname.includes('/agenda')) {


            Swal.fire({

                title: 'Primeros Pasos',
                text: 'Deberias continuar creando tu primer PROVEEDOR.',
                icon: 'info',
                showCancelButton: false,

            });

            iconoAgenda('providers');

            setLvl(6, tokenCSRF);



        }// Cierre Primeros Pasos en "/agenda"


        // Mensaje Categorias
        if (!respuesta.tutorial && respuesta.tutorial_lvl === 2 && window.location.pathname.includes('/categorias')) {

            Swal.fire({
                title: 'Estas son tus Categorias',
                text: "En cada Categoria puedes indicar el indice de GANANCIA que deseas aplicar a los articulos. Esto es muy útil para clasificar tus productos y posteriormente para hacer AUMENTOS GENERALES.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Nada de tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        text: 'Piensa en cualquier producto de tu inventario: A que Categoria pertenece? También puedes utilizar nombres genericos como "Otros" y una ganancia de 1 (sin ganancia), pero será mas dificil clasificarlos en el futuro.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        Swal.fire({

                            title: 'Primeros Pasos',
                            text: 'Cuando estes listo deberias crear tu primer FABRICANTE.',
                            icon: 'info',
                            showCancelButton: false,

                        });
                    });

                    iconoTutorial('agenda');
                    setLvl(3, tokenCSRF);

                } else {

                    // Desactivar tutorial
                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo en Ayuda',
                        'success'
                    );
                }
            });
        }

        // Mensaje Fabricantes
        if (!respuesta.tutorial && respuesta.tutorial_lvl === 4 && window.location.pathname.includes('/fabricantes')) {

            Swal.fire({
                title: 'Estos son tus Fabricantes',
                text: "En cada Fabricante puedes almacenar todos los datos relacionados con el mismo, igual que una agenda.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Odio los tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros pasos',
                        text: 'No todos los datos son obligatorios, pero es muy útil agendar tus fabricantes. Luego podemos hacer aumentos generales a los productos de cada Fabricante.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        Swal.fire({

                            title: 'Primeros Pasos',
                            text: 'Cuando estes listo deberias crear tu primer PROVEEDOR.',
                            icon: 'info',
                            showCancelButton: false,

                        });
                    });

                    iconoTutorial('agenda');
                    setLvl(5, tokenCSRF);

                } else {

                    // Desactivar tutorial
                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo en Ayuda',
                        'success'
                    );
                }
            });
        }

        // Mensaje Providers
        if (!respuesta.tutorial && respuesta.tutorial_lvl === 6 && window.location.pathname.includes('/providers')) {

            Swal.fire({
                title: 'Estos son tus Proveedores',
                text: "En cada Proveedor puedes almacenar todos los datos relacionados con el mismo, también definir el indice de Ganancia que aplicas a cada uno.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Odio los tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        text: 'Puedes utilizar nombres genericos como "Otros" y una ganancia de 1 (sin ganancia), pero será mas dificil clasificarlos en el futuro.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        Swal.fire({

                            title: 'Primeros Pasos',
                            text: 'Cuando hayas terminado estarás listo para crear tu primer PRODUCTO! Puedes encontrarlo en la barra de herramientas.',
                            icon: 'info',
                            showCancelButton: false,

                        });
                    });

                    iconoTutorial('new-prod');

                    setLvl(7, tokenCSRF);

                } else {

                    // Desactivar tutorial
                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo en Ayuda',
                        'success'
                    );
                }
            });
        }



        if (!respuesta.tutorial && respuesta.tutorial_lvl === 7 && window.location.pathname.includes('/producto')) {




            // Mensaje Agenda
            Swal.fire({
                title: 'Aquí puedes crear nuevos productos',
                text: "Solo debes darle un nombre y un precio, también puedes indicar la cotización del Dólar Hoy (prometo recordarlo en futuros productos).",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Odio los tutoriales',
                confirmButtonText: 'Genial, sigamos!'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        text: 'Tu producto ya tiene asignado un código y puedes escoger que tipo de Ganancia deseas aplicar para calcular el precio de venta. Recuerda que el IVA se calcula como 21%.',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        // Siguiente mensaje, alerta en PRODUCTO
                        iconoTutorial('buscador');

                        Swal.fire({

                            title: 'Primeros pasos',
                            text: 'Cuando hayas creado tu primer producto estará disponible en el BUSCADOR. Ya puedes organizar tu Agenda y añadir todos los productos que quieras.',
                            icon: 'info',
                            showCancelButton: false,

                        });

                        iconoTutorial('buscador');

                        setLvl(8, tokenCSRF);

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

        if (!respuesta.tutorial && respuesta.tutorial_lvl === 8 && window.location.pathname === '/') {


            Swal.fire({

                title: 'Primeros Pasos',
                text: 'Ya puedes buscar tu producto! Tienes 3 opciones de busqueda: por listado, por nombre y por código.',
                icon: 'info',
                showCancelButton: false,

            }).then(() => {

                Swal.fire({

                    title: 'Todo tiene un final...',
                    text: 'TusListas puede hacer mucho mas por ti y tu comercio/empresa, fue diseñada para organizar tus listas de precios y mantenerlos actualizados. Puedes encontrar toda la información en AYUDA.',
                    icon: 'info',
                    showCancelButton: false,

                }).then(() => {

                    Swal.fire({

                        title: 'Todo tiene un final...',
                        text: 'Haz finalizado el tutorial Primeros Pasos, eres un usuario especial :)',
                        icon: 'info',
                        showCancelButton: false,
    
                    });
                });

                // Desactivar tutorial
                activarDesactivar(1, tokenCSRF);
            });
        }




        function iconoTutorial(elemento, eliminar = false) {

            if (!eliminar) {
                // Crear icono

                const elementoDOM = document.querySelector('#sidebar-' + elemento);

                const notif = document.createElement('I');
                notif.classList.add('sidebar__alert-tutorial', 'fa-solid', 'fa-circle-exclamation');
                notif.id = 'sidebar-tutorial-alert';

                elementoDOM.appendChild(notif);

            } else {
                // Eliminar icono
                const icono = document.querySelector('#sidebar-tutorial-alert');
                icono.remove();

            }
        }


        function iconoAgenda(elemento, eliminar = false) {

            if (!eliminar) {
                // Crear icono
                const elementoDOM = document.querySelector('#agenda-' + elemento);

                const notif = document.createElement('I');
                notif.classList.add('agenda__alert-tutorial', 'fa-solid', 'fa-circle-exclamation');
                notif.id = 'agenda-tutorial-alert';

                elementoDOM.appendChild(notif);
            } else {
                // Eliminar icono
                const icono = document.querySelector('#agenda-tutorial-alert');
                icono.remove();

            }

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

        // Modifica la elección del usuario (0 ver tutoriales - 1 no ver tutoriales)
        async function setLvl(lvl, token) {

            try {
                const url = '/api/tutorial/set-lvl';
                const datos = new FormData();

                datos.append('lvl', lvl);

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: datos
                });
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }
        }





        // Consulta si el usuario desea ver tutorial, devuelve bool y tutorial_lvl
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