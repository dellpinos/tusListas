import Swal from 'sweetalert2';

(function () {

    if (document.querySelector('#name-empresa')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const nameEmpresa = document.querySelector('#name-empresa');
        const nuevoUser = document.querySelector('#email-user');

        const btnNuevoUser = document.querySelector('#btn-new-user');
        const btnNameEmpresa = document.querySelector('#btn-name-empresa');



        main();

        btnNuevoUser.addEventListener('click', async () => {

            console.log('Click!');

            // Alerta advertencia

            const email = nuevoUser.value;

            const resultado = await alertaInvitacion(email, tokenCSRF);

            // Enviar Email





        });


        btnNameEmpresa.addEventListener('click', async () => {

            // Alerta advertencia
            const name = nameEmpresa.value;

            // Cambiar Nombre
            const resultado = await alertaName(name, tokenCSRF);

            if(resultado) {
                location.reload();
            } else {

            }

            


        });

        async function validaciones(resultado) {

            // Evalua el array "errors" dentro del resultado, identificando el campo y el mensaje
            for (let campo in resultado.errors) {
                if (resultado.errors.hasOwnProperty(campo)) {
                    let mensajesDeError = resultado.errors[campo];

                    for (let i = 0; i < mensajesDeError.length; i++) {

                        // Mensaje de error, recibe el campo, el mensaje y el tipo (categoria, provider o fabricante)
                        mensajeError(mensajesDeError[i]);

                    }
                }
            }
        }

        function mensajeError(mensaje) { 

            // Eliminar errores anteriores
            const errores = document.querySelectorAll(".alerta__error");
            errores.forEach(element => {
                element.remove();
            });
            const bordeErrores = document.querySelectorAll(".borde__error");
            bordeErrores.forEach(element => {
                element.classList.remove("borde__error");
            });

            // Crear nuevos mensajes de error
            const mensajeParrafo = document.createElement('P');
            mensajeParrafo.classList.add('alerta__error');
            mensajeParrafo.textContent = mensaje;

            let padre = nameEmpresa.parentNode;

            padre.appendChild(mensajeParrafo);
        }






        async function main() {

            const usuarios = await all();

            cargarTabla(usuarios);

            const empresa = await name();

            nameEmpresa.value = empresa;



        }

        function cargarTabla(users) {

            const owner = users.owner;
            const usuarios = users.users;

            if (!users.length <= 1) {
                const tabla = document.querySelector('#owner-table');

                tabla.innerHTML = `
                <thead class="table__thead">
                    <tr>
                        <th scope="col" class="table__th">Nombre</th>
                        <th scope="col" class="table__th">Username</th>
                        <th scope="col" class="table__th">Email</th>
                        <th scope="col" class="table__th">Enlace</th>
                    </tr>
                </thead>
                `;

                const tablaBody = document.createElement('TBODY');
                tablaBody.classList.add('table__tbody');

                tabla.appendChild(tablaBody);

                usuarios.forEach(usuario => {

                    if (usuario.id !== owner.id) {

                        const tRow = document.createElement('TR');
                        tRow.classList.add('table__tr');

                        tRow.innerHTML += `
                        <td class="table__td">${usuario.name}</td>
                        <td class="table__td">${usuario.username}</td>
                        <td class="table__td">${usuario.email}</td>
                        `;

                        const tEnlace = document.createElement('TD');
                        tEnlace.classList.add('table__td');

                        const enlace = document.createElement('A');
                        enlace.classList.add('table__accion', 'table__accion--editar');
                        
                        enlace.textContent = "Eliminar";

                        tEnlace.appendChild(enlace);
                        tRow.appendChild(tEnlace);

                        tablaBody.appendChild(tRow);

                        tEnlace.addEventListener('click', async (e) => {
                            e.preventDefault();

                            // Eliminar Usuario y recargar tablas
                            await alertaDelete(usuario.id, tokenCSRF);

                        });

                    }
                });
            } else {

                console.log("no hay usuarios")
            }
        }

        async function cambiarNormbre(name, token) {

            const url = '/api/owner-tools/update';

            try {
                const datos = new FormData();
                datos.append('name', name);
                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: datos
                });

                const resultado = await respuesta.json();
                if(resultado.errors) {
                    validaciones(resultado);
                    return false;
                } else {
                    return resultado;

                }

                
            } catch (error) {
                console.log(error);
            }

        }

        async function all() {
            const url = '/api/owner-tools/all';

            try {

                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }

        }

        async function name() {
            const url = '/api/owner-tools/name';

            try {

                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error);
            }

        }

        //////

        async function enviarInvitacion(email, token) {

            const url = '/api/invitaciones/create';

            try {
                const datos = new FormData();
                datos.append('email', email);
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

        ///

        



        async function alertaInvitacion(email, token) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: `Enviar invitación a ${email}`,
                text: "Este usuario formará parte de la empresa y podrá modificar la información.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {

                        try {

                            const resultado = await enviarInvitacion(email, token);

                            if (resultado) {
                                swalWithBootstrapButtons.fire(
                                    'Invitación enviada',
                                    'La invitación ha sido enviada con éxito',
                                    'success'
                                );
                                    nuevoUser.value = ''; // Limpiar campo
                                
                            } else {

                                swalWithBootstrapButtons.fire(
                                    'Algo salió mal',
                                    'Error',
                                    'error'
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
                        'No se ha enviado la invitación',
                        'error'
                    );
                }
            });
        }


        ///////



        async function alertaName(name, token) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Estas seguro?',
                text: "Esta acción cambia el nombre de la Empresa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Modificar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {

                        try {

                            const resultado = await cambiarNormbre(name, token);

                            if (resultado) {
                                swalWithBootstrapButtons.fire(
                                    'Modificado',
                                    'Ha sido modificado con éxito',
                                    'success'
                                );
                                setTimeout(() => {
                                    location.reload(); // Recarga pa página para afectar al heading principal (esta escrito en el HTML)
                                    
                                }, 1500);
                                
                            } else {

                                swalWithBootstrapButtons.fire(
                                    'Algo salió mal',
                                    'Error',
                                    'error'
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
                    );
                }
            });
        }


        async function alertaDelete(id, token) {

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

                        try {

                            const resultado = await destroy(id, token);

                            if (resultado) {
                                swalWithBootstrapButtons.fire(
                                    'Eliminado/a',
                                    'Ha sido destruido :(',
                                    'success'
                                );
                                await main();
                            } else {

                                swalWithBootstrapButtons.fire(
                                    'No puede ser eliminado',
                                    'Error',
                                    'error'
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
                    );
                }
            });
        }


        // Toma un id a eliminar y un tipo, este puede ser "fabricante, provider o fabricante". El tipo es parte de la URL hacia la API
        // tokenCSRF debe estar definido como variable global dentro del archivo que importa estas funciones
        async function destroy(id, token) {

            try {
                const datos = new FormData();
                datos.append('id', id);

                const url = '/api/owner-tools/destroy';
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