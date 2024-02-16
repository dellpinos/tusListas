import Swal from 'sweetalert2';

(async function () {

    if (document.querySelector('.sidebar')) {

        // Consultar
        const respuesta = await consultar();

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

        if (!respuesta.tutorial) {

            const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (respuesta.tutorial_lvl === 0 && window.location.pathname === '/buscador') {
                // Iniciar tutorial

                Swal.fire({
                    title: '¡ Bienvenido a TusListas !',
                    html: '<p class="sa__msj-count">1/21</p><p class="sa__text" >Este es un pequeño tutorial para guiarte en tus Primeros Pasos.</p>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#0284C7',
                    cancelButtonColor: '#EF4444',
                    cancelButtonText: 'Nada de tutoriales',
                    confirmButtonText: 'Genial, sigamos!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Siguiente mensaje, alerta en AGENDA
                        iconoSidebar('agenda');

                        Swal.fire({

                            title: 'Primeros Pasos',
                            html: '<p class="sa__msj-count">2/21</p><p class="sa__text" >Deberías comenzar por personalizar tu AGENDA<i class="sa__alert-tutorial fa-solid fa-circle-exclamation"></i>, puedes encontrarla en la barra de herramientas.</p>',
                            icon: 'info',
                            showCancelButton: false,

                        });


                        setLvl(1, tokenCSRF);
                    } else {
                        // Desactivar tutorial

                        activarDesactivar(1, tokenCSRF);

                        Swal.fire(
                            'Tutorial desactivado',
                            'Puedes volver a activarlo en la sección Ayuda.',
                            'success'
                        );
                    }
                });
            } // Cierre Primeros Pasos en "/"

            if (respuesta.tutorial_lvl === 1) {

                // Siguiente mensaje, alerta en AGENDA
                iconoSidebar('agenda');

                if (window.location.pathname.includes('/agenda')) {

                    // Mensaje Agenda
                    Swal.fire({
                        title: 'Esta es tu agenda',
                        html: '<p class="sa__msj-count">3/21</p><p class="sa__text" >Aquí puedes almacenar tus Proveedores, las Categorías de tus productos y sus Fabricantes. En cada Categoría y Proveedor, puedes indicar el índice de GANANCIA que deseas aplicar a sus productos. Esto es muy útil para clasificar tus productos y posteriormente para hacer Aumentos Generales.</p>',
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
                                html: '<p class="sa__msj-count">4/21</p><p class="sa__text" >Deberias continuar creando tu primer CATEGORIA.</p>',
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
                                'Puedes volver a activarlo en la sección Ayuda.',
                                'success'
                            );
                        }
                    });
                }
            }

            else if (respuesta.tutorial_lvl === 3) {

                iconoSidebar('agenda');

                if (window.location.pathname.includes('/agenda')) {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        html: '<p class="sa__msj-count">8/21</p><p class="sa__text" >Deberías continuar creando tu primer FABRICANTE.</p>',
                        icon: 'info',
                        showCancelButton: false,

                    });

                    iconoAgenda('fabricantes');

                    setLvl(4, tokenCSRF);
                }

            } else if (respuesta.tutorial_lvl === 5) {

                iconoSidebar('agenda');


                if (window.location.pathname.includes('/agenda')) {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        html: '<p class="sa__msj-count">12/21</p><p class="sa__text" >Deberías continuar creando tu primer PROVEEDOR.</p>',
                        icon: 'info',
                        showCancelButton: false,
                    });

                    iconoAgenda('providers');

                    setLvl(6, tokenCSRF);

                }
            }// Cierre Primeros Pasos en "/agenda"

            // Mensaje Categorias
            if (respuesta.tutorial_lvl === 2) {

                iconoSidebar('agenda');

                if (document.querySelector('#agenda-categorias')) {

                    iconoAgenda('categorias');
                }

                if (window.location.pathname.includes('/categorias')) {

                    Swal.fire({
                        title: 'Estas son tus Categorías',
                        html: '<p class="sa__msj-count">5/21</p><p class="sa__text" >En cada Categoría puedes indicar el índice de GANANCIA que deseas aplicar a los artículos. Esto es muy útil para clasificar tus productos y posteriormente para hacer AUMENTOS GENERALES.</p>',
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
                                html: '<p class="sa__msj-count">6/21</p><p class="sa__text" >Piensa en cualquier producto de tu inventario: ¿A qué Categoría pertenece? También puedes utilizar nombres genéricos como "Otros" y una ganancia de 1 (sin ganancia), pero será más difícil clasificarlos en el futuro.</p>',
                                icon: 'info',
                                showCancelButton: false,

                            }).then(() => {

                                Swal.fire({

                                    title: 'Primeros Pasos',
                                    html: '<p class="sa__msj-count">7/21</p><p class="sa__text" >Cuando estes listo deberias crear tu primer FABRICANTE.</p>',
                                    icon: 'info',
                                    showCancelButton: false,

                                });
                            });

                            iconoSidebar('agenda');

                            setLvl(3, tokenCSRF);

                        } else {

                            // Desactivar tutorial
                            activarDesactivar(1, tokenCSRF);

                            Swal.fire(
                                'Tutorial desactivado',
                                'Puedes volver a activarlo en la sección Ayuda.',
                                'success'
                            );
                        }
                    });
                }
            }

            // Mensaje Fabricantes
            if (respuesta.tutorial_lvl === 4) {

                iconoSidebar('agenda');

                if (document.querySelector('#agenda-fabricantes')) {

                    iconoAgenda('fabricantes');
                }

                if (window.location.pathname.includes('/fabricantes')) {

                    Swal.fire({
                        title: 'Estos son tus Fabricantes',
                        html: '<p class="sa__msj-count">9/21</p><p class="sa__text" >En cada Fabricante puedes almacenar todos los datos relacionados con el mismo, igual que una agenda.</p>',
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
                                html: '<p class="sa__msj-count">10/21</p><p class="sa__text" >No todos los datos son obligatorios, pero es muy útil agendar tus fabricantes. Luego podemos hacer aumentos generales a los productos de cada Fabricante.</p>',
                                icon: 'info',
                                showCancelButton: false,

                            }).then(() => {

                                Swal.fire({

                                    title: 'Primeros Pasos',
                                    html: '<p class="sa__msj-count">11/21</p><p class="sa__text" >Cuando estes listo deberías crear tu primer PROVEEDOR.</p>',
                                    icon: 'info',
                                    showCancelButton: false,

                                });
                            });

                            iconoSidebar('agenda');
                            setLvl(5, tokenCSRF);

                        } else {

                            // Desactivar tutorial
                            activarDesactivar(1, tokenCSRF);

                            Swal.fire(
                                'Tutorial desactivado',
                                'Puedes volver a activarlo en la sección Ayuda.',
                                'success'
                            );
                        }
                    });
                }
            }

            // Mensaje Providers
            if (respuesta.tutorial_lvl === 6) {

                iconoSidebar('agenda');

                if (document.querySelector('#agenda-providers')) {

                    iconoAgenda('providers');
                }

                if (window.location.pathname.includes('/providers')) {

                    Swal.fire({
                        title: 'Estos son tus Proveedores',
                        html: '<p class="sa__msj-count">13/21</p><p class="sa__text" >En cada Proveedor puedes almacenar todos los datos relacionados con el mismo y definir el índice de Ganancia que aplicas a cada uno.</p>',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#0284C7',
                        cancelButtonColor: '#EF4444',
                        cancelButtonText: 'No más tutoriales',
                        confirmButtonText: 'Genial, sigamos!'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            Swal.fire({

                                title: 'Primeros Pasos',
                                html: '<p class="sa__msj-count">14/21</p><p class="sa__text" >Puedes utilizar nombres genéricos como "Otros" y una ganancia de 1 (sin ganancia), pero será más difícil clasificarlos en el futuro.</p>',
                                icon: 'info',
                                showCancelButton: false,

                            }).then(() => {

                                Swal.fire({

                                    title: 'Primeros Pasos',
                                    html: '<p class="sa__msj-count">15/21</p><p class="sa__text" >Cuando hayas terminado estarás listo para crear tu primer PRODUCTO! Puedes encontrarlo en la barra de herramientas.</p>',
                                    icon: 'info',
                                    showCancelButton: false,

                                });
                            });

                            iconoSidebar('agenda', true); // eliminar icono
                            iconoSidebar('new-prod');

                            setLvl(7, tokenCSRF);

                        } else {

                            // Desactivar tutorial
                            activarDesactivar(1, tokenCSRF);

                            Swal.fire(
                                'Tutorial desactivado',
                                'Puedes volver a activarlo en la sección Ayuda.',
                                'success'
                            );
                        }
                    });
                }
            }

            if (respuesta.tutorial_lvl === 7) {

                iconoSidebar('new-prod');

                if (window.location.pathname.includes('/producto')) {

                    // Mensaje Agenda
                    Swal.fire({
                        title: 'Aquí puedes crear nuevos productos',
                        html: '<p class="sa__msj-count">16/21</p><p class="sa__text" >Solo debes darle un nombre y un precio. También puedes indicar la cotización del Dólar Hoy (prometo recordarlo en futuros productos).</p>',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#0284C7',
                        cancelButtonColor: '#EF4444',
                        cancelButtonText: 'Me cansé',
                        confirmButtonText: 'Genial, sigamos!'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            Swal.fire({

                                title: 'Primeros Pasos',
                                html: '<p class="sa__msj-count">17/21</p><p class="sa__text" >Tu producto ya tiene asignado un código y puedes escoger que tipo de Ganancia deseas aplicar para calcular el precio de venta. Recuerda que el IVA se calcula como 21%.</p>',
                                icon: 'info',
                                showCancelButton: false,

                            }).then(() => {

                                Swal.fire({

                                    title: 'Primeros pasos',
                                    html: '<p class="sa__msj-count">18/21</p><p class="sa__text" >Cuando hayas creado tu primer producto, estará disponible en el BUSCADOR. Ya puedes organizar tu Agenda y añadir todos los productos que desees.</p>',
                                    icon: 'info',
                                    showCancelButton: false,

                                });

                                iconoSidebar('new-prod', true); // eliminar icono
                                iconoSidebar('buscador');

                                setLvl(8, tokenCSRF);

                            });

                        } else {

                            // Desactivar tutorial
                            activarDesactivar(1, tokenCSRF);

                            Swal.fire(
                                'Tutorial desactivado',
                                'Puedes volver a activarlo en la sección Ayuda.',
                                'success'
                            );
                        }
                    });
                }
            }

            if (respuesta.tutorial_lvl === 8) {

                iconoSidebar('buscador');

                if (window.location.pathname === '/buscador') {

                    Swal.fire({

                        title: 'Primeros Pasos',
                        html: '<p class="sa__msj-count">19/21</p><p class="sa__text" >Ya puedes buscar tu producto. Tienes tres opciones de búsqueda: por listado, por nombre y por código.</p>',
                        icon: 'info',
                        showCancelButton: false,

                    }).then(() => {

                        Swal.fire({

                            title: 'Todo tiene un final...',
                            html: '<p class="sa__msj-count">20/21</p><p class="sa__text" >TusListas puede hacer mucho más por ti y tu comercio/empresa. Fue diseñada para organizar tus listas de precios y mantenerlos actualizados. Puedes encontrar toda la información en la sección Ayuda.</p>',
                            icon: 'info',
                            showCancelButton: false,

                        }).then(() => {

                            Swal.fire({

                                title: 'Todo tiene un final...',
                                html: '<p class="sa__msj-count">21/21</p><p class="sa__text" >Haz finalizado el tutorial "Primeros Pasos", eres un usuario especial :)</p>',
                                icon: 'info',
                                showCancelButton: false,

                            });
                        });

                        // Desactivar tutorial
                        activarDesactivar(1, tokenCSRF);
                        iconoSidebar('buscador', true); // eliminar icono
                    });
                }
            }

            function iconoSidebar(elemento, eliminar = false) {

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
        }
    }
})();