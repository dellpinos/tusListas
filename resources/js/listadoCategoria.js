import Swal from 'sweetalert2';
import swiper from './swiper';

(function () {
    if (document.querySelector('#categorias-registros')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const botones = document.querySelectorAll('.categoria__contenedor button');

        const contRegistros = document.querySelector('#categorias-registros'); // contenedor

        let categoriasArray = [];

        // Obtener todas las categorias
        listadoCategorias();

        // Consulta DB
        async function listadoCategorias() {
            try {

                const url = '/api/categorias/all';

                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                categoriasArray = resultado.categorias; // array de productos

                mostrarElementos();


            } catch (error) {
                console.log('No carga el listado');
            }
        }

        function mostrarElementos() {

            limpiarElementos(contRegistros);

            if (categoriasArray.length === 0) {

                const contenedorVacio = document.querySelector('#mensaje-vacio');

                const textoNoCat = document.createElement('P');
                textoNoCat.textContent = "No se encontraron categorias";
                textoNoCat.classList.add('mensaje__vacio');
                contenedorVacio.appendChild(textoNoCat);
                return;
            }

            categoriasArray.forEach(categoria => {

                const contenedor = document.createElement('DIV');
                contenedor.classList.add('categoria__contenedor', 'swiper-slide');

                const catHeading = document.createElement('H3');
                catHeading.textContent = categoria.nombre;

                const catParrafo = document.createElement('P');

                const catSpan1 = document.createElement('SPAN');
                catSpan1.classList.add('font-bold');
                catSpan1.textContent = "Ganancia: ";

                const catSpan2 = document.createElement('SPAN');
                catSpan2.classList.add('categoria__ganancia');
                catSpan2.textContent = categoria.ganancia;

                const contenedorSM = document.createElement('DIV');
                contenedorSM.classList.add('categoria__contenedor-boton', 'categoria__contenedor-boton--sm');

                const catEnlace = document.createElement('A');
                catEnlace.setAttribute('href', `/categoria/categoria-edit/${categoria.id}`);
                catEnlace.classList.add('categoria__boton', 'categoria__boton--modificar');
                catEnlace.textContent = "Ver / Editar";

                const catBtn = document.createElement('BUTTON');
                catBtn.classList.add('categoria__boton', 'categoria__boton--eliminar');
                catBtn.textContent = "Eliminar";

                catBtn.addEventListener('click', () => {
                    alertaDelete(categoria.id, 'categoria', categoriasArray);
                });

                catParrafo.appendChild(catSpan1);
                catParrafo.appendChild(catSpan2);

                contenedorSM.appendChild(catEnlace);
                contenedorSM.appendChild(catBtn);

                contenedor.appendChild(catHeading);
                contenedor.appendChild(catParrafo);
                contenedor.appendChild(contenedorSM);

                contRegistros.appendChild(contenedor);

                swiper.update();

            }); // Fin cada categoria
        }

        function limpiarElementos(padre) {
            while (padre.firstChild) {
                padre.removeChild(padre.firstChild);
            }
        }

        function alertaDelete(id, tipo, array = null) {

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
                confirmButtonText: 'Si, destruir!',
                cancelButtonText: 'No, era una prueba!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    (async function () {
                        const resultado = await destroy(id, tipo);

                        if (resultado.eliminado) {
                            swalWithBootstrapButtons.fire(
                                'Eliminado/a',
                                firstCap(tipo) + ' ha sido destruido :(',
                                'success'

                            );
                            if(array) {
                                categoriasArray = filtrarVirtualDOM(array, id); // si hay un array va a filtrarlo
                                mostrarElementos();
                            }

                        } else {

                            swalWithBootstrapButtons.fire(
                                'No puede ser eliminado',
                                'Hay ' + resultado.cantidad_productos + ' producto/s relacionado/s. Puedes editar ' + tipo + ' o el/los producto/s.',
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
                    )
                }
            });
        }

        function filtrarVirtualDOM(array, id) {
            array = array.filter(element => element.id !== id);
            return array;
        }

        async function destroy(id, tipo) {

            try {
                const datos = new FormData();
                datos.append('id', id);

                const url = '/api/' + tipo + 's/destroy';
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
        /* Convierte en mayuscula la primer letra del string */
        function firstCap(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    }
})();