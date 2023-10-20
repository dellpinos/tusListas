
import swiper from './swiper';
import Swal from 'sweetalert2';
import * as helpers from './helpers';

(function () {

    if (document.querySelector('#providers-registros')) {

        let providersArray = [];
        let providersArrayFiltrado = [];
        let busquedaLength = 0;
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const contRegistros = document.querySelector('#providers-registros'); // contenedor
        const inputBusqueda = document.querySelector('#provider-formulario');
        const campoBuscador = document.querySelector('.formulario__contenedor-busqueda');
        const contenedorVacio = document.querySelector('#mensaje-vacio');

        // Obtener todas las providers
        listadoproviders();

        campoBuscador.onclick = function () {
            inputBusqueda.focus();
        }

        inputBusqueda.addEventListener('input', (e) => {

            if (busquedaLength > e.target.value.length) {

                // El usuario esta borrando 
                providersArrayFiltrado = providersArray;
                mostrarElementos();
            }

            busquedaLength = e.target.value.length;

            if (e.target.value.length >= 2) {
                buscarCoincidenciasMemoria(e);
            }
        });

        // Vacia el campo de busqueda
        inputBusqueda.addEventListener('blur', (e) => {
            e.target.value = '';
        });

        // Consulta DB
        async function listadoproviders() {
            try {

                const url = '/api/providers/all';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                providersArray = resultado.providers; // array de providers
                providersArrayFiltrado = resultado.providers;

                mostrarElementos();

            } catch (error) {
                console.log('No carga el listado' + error);
            }
        }

        function mostrarElementos() {

            // Elimina los elementos hijos
            limpiarElementos(contRegistros);
            limpiarElementos(contenedorVacio);

            if (providersArrayFiltrado.length === 0) {

                const textoNoCat = document.createElement('P');

                limpiarElementos(contenedorVacio);

                textoNoCat.textContent = "No se encontraron proveedores";
                textoNoCat.classList.add('mensaje__info');
                contenedorVacio.appendChild(textoNoCat);
                return;
            }

            providersArrayFiltrado.forEach(provider => {

                const contenedor = document.createElement('DIV');
                contenedor.classList.add('provider__contenedor', 'swiper-slide');

                const catHeading = document.createElement('H3');
                catHeading.textContent = provider.nombre;

                const catParrafo = document.createElement('P');
                catParrafo.classList.add('provider__ganancia');
                catParrafo.textContent = "Ganancia: " + provider.ganancia;

                provider.email = provider.email !== null ? provider.email : '';
                const catParrafo2 = document.createElement('P');
                catParrafo2.textContent = "Email: " + provider.email;

                provider.telefono = provider.telefono !== null ? provider.telefono : '';
                const catParrafo3 = document.createElement('P');
                catParrafo3.textContent = "Teléfono: " + provider.telefono;

                provider.vendedor = provider.vendedor !== null ? provider.vendedor : '';
                const catParrafo4 = document.createElement('P');
                catParrafo4.textContent = "Vendedor: " + provider.vendedor;

                provider.web = provider.web !== null ? provider.web : '';
                const catParrafo5 = document.createElement('P');
                catParrafo5.textContent = "Web: " + provider.web;

                const contenedorSM = document.createElement('DIV');
                contenedorSM.classList.add('provider__contenedor-boton', 'formulario__contenedor-boton--sm');

                const catEnlace = document.createElement('A');
                catEnlace.setAttribute('href', `/provider/provider-edit/${provider.id}`);
                catEnlace.classList.add('provider__boton', 'provider__boton--modificar');
                catEnlace.textContent = "Ver / Editar";

                const catBtn = document.createElement('BUTTON');
                catBtn.classList.add('provider__boton', 'provider__boton--eliminar');
                catBtn.textContent = "Eliminar";

                catBtn.addEventListener('click', async () => {
                    try {
                        await alertaDelete(provider.id, 'provider', true, tokenCSRF);
                        mostrarElementos();

                    } catch (error) {
                        console.log(error);
                    }
                });

                contenedorSM.appendChild(catEnlace);
                contenedorSM.appendChild(catBtn);
                contenedor.appendChild(catHeading);
                contenedor.appendChild(catParrafo);
                contenedor.appendChild(catParrafo2);
                contenedor.appendChild(catParrafo3);
                contenedor.appendChild(catParrafo4);
                contenedor.appendChild(catParrafo5);
                contenedor.appendChild(contenedorSM);
                contRegistros.appendChild(contenedor);

                swiper.update();

            }); // Fin cada provider
        }

        function limpiarElementos(padre) {
            while (padre.firstChild) {
                padre.removeChild(padre.firstChild);
            }
        }

        // Toma un id a eliminar, un tipo (provider, provider o provider) que será parte de la url hacia la API
        // y un array (opcional) en caso de utilizar vitualDOM
        // Contiene una llamada al método mostrarElementos(), este debe contener el scripting de los elementos HTML del paginador
        // Contiene una llamada a filtrarVirtualDOM(), es un helper
        // Contiene una llamada a destroy(), es un helper (id y tipo son pasados a destroy())
        async function alertaDelete(id, tipo, flag = false, token = null) {

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

                            const resultado = await destroy(id, tipo, token);

                            if (resultado.eliminado) {
                                swalWithBootstrapButtons.fire(
                                    'Eliminado/a',
                                    helpers.firstCap(tipo) + ' ha sido destruido :(',
                                    'success'
                                );
                                if (flag) {
                                    providersArray = filtrarVirtualDOM(providersArray, id); // si hay un array va a filtrarlo
                                    providersArrayFiltrado = providersArray;
                                    mostrarElementos();
                                }
                            } else {

                                swalWithBootstrapButtons.fire(
                                    'No puede ser eliminado',
                                    'Hay ' + resultado.cantidad_productos + ' producto/s relacionado/s. Puedes editar ' + tipo + ' o el/los producto/s.',
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

        // Toma un array (el vitualDOM) y un id a eliminar
        function filtrarVirtualDOM(array, id) {
            array = array.filter(element => element.id !== id);
            return array;
        }

        // Toma un id a eliminar y un tipo, este puede ser "provider, provider o provider". El tipo es parte de la URL hacia la API
        // tokenCSRF debe estar definido como variable global dentro del archivo que importa estas funciones
        async function destroy(id, tipo, token) {

            try {
                const datos = new FormData();
                datos.append('id', id);

                const url = '/api/' + tipo + 's/destroy';
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

        // Buscador
        function buscarCoincidenciasMemoria(e) {
            const busqueda = e.target.value; // input del usuario
            const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

            providersArrayFiltrado = providersArrayFiltrado.filter(provider => { // filtra elementos en memoria
                if (provider.nombre.toLowerCase().search(Regex) !== -1) {
                    return provider;
                }
            });

            // Recargar elementos
            mostrarElementos();
        }
    }
})();