import Swal from 'sweetalert2';

(async function () {

    if (document.querySelector('.sidebar')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Consultar
        const respuesta = await consultar();

        if (!respuesta) {
            // Iniciar tutorial


            // Mensaje inicial del tutorial


            Swal.fire({
                title: 'Bienvenido a TusListas!',
                text: "Este es un pequeño tutorial para guiarte en tus primeros pasos",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'No, gracias',
                confirmButtonText: 'Genial, sigamos!'
              }).then((result) => {
                if (result.isConfirmed) {

                    // Siguiente mensaje


                  Swal.fire({

                    title: 'Primeros pasos',
                    text: 'Deberias comenzar por personalizar tu AGENDA, puedes encontrarla en la barra de herramientas',

                    icon: 'info',
                    showCancelButton: false,

                 });
                } else {
                    console.log('Eliminar bool de tutorial');

                    activarDesactivar(1, tokenCSRF);

                    Swal.fire(
                        'Tutorial desactivado',
                        'Puedes volver a activarlo desde tu perfil',
                        'success'
                      )
                }
              });










              return;


            Swal.fire({
                title: '<strong>Bienvenido a TusListas! </strong>',
                icon: 'info',
                html:
                    //'You can use <b>bold text</b>, ' +

                    'Este es un pequeño tutorial para guiarte en tus primeros pasos. <br> ' +

                    'Deseas seguir con el tutorial ?',

                    //'TusListas es una aplicación que te ayuda a organizar tu negocio, tener listas de precios actualizadas y siempre al alcance',


                showCloseButton: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Genial!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i> No, gracias',
                cancelButtonAriaLabel: 'Thumbs down'
            });






















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