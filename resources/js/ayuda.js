
(function () {

    if (document.querySelector('.ayuda__grid')) {

        document.addEventListener('DOMContentLoaded', async () => {

            const boton = document.querySelector('#ayuda-boton-tutorial');
            const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const resultado = await consultar();

            cambiarBoton(resultado.tutorial, boton);

            boton.addEventListener('click', async () => {

                if(resultado.tutorial) {
                    await activarDesactivar(0, tokenCSRF);
                } else {
                    await activarDesactivar(1, tokenCSRF);
                }

                const respuesta = await consultar();

                cambiarBoton(respuesta.tutorial, boton);


            });
        });

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

        function cambiarBoton(tutorial, btn) {

            if (!tutorial) {

                btn.textContent = "Activado";
                btn.classList.remove('ayuda__boton--inactivo');
                btn.classList.add('ayuda__boton--activo');


            } else {

                btn.textContent = "Desactivado";
                btn.classList.add('ayuda__boton--inactivo');
                btn.classList.remove('ayuda__boton--activo');
            }

        }





    }

})();