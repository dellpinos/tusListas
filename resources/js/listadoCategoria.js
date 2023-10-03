
import swiper from './swiper';
import Swal from 'sweetalert2';
import * as helpers from './helpers';

(function () {
    if (document.querySelector('#categorias-registros')) {

        let categoriasArray = [];
        let categoriasArrayFiltrado = [];
        let busquedaLength = 0;
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const contRegistros = document.querySelector('#categorias-registros'); // contenedor
        const inputBusqueda = document.querySelector('#categoria-formulario');
        const campoBuscador = document.querySelector('.formulario__contenedor-busqueda');
        const contenedorVacio = document.querySelector('#mensaje-vacio');

        // Obtener todas las categorias
        listadoCategorias();

        campoBuscador.onclick = function() {
            inputBusqueda.focus();
        }



        
        inputBusqueda.addEventListener('input', (e) => {

            if(busquedaLength > e.target.value.length) {

                // El usuario esta borrando 
                categoriasArrayFiltrado = categoriasArray;
                mostrarElementos();
            }
            
            busquedaLength = e.target.value.length;

            if(e.target.value.length >= 2) {
                buscarCoincidenciasMemoria(e);
            }
        });





        // Vacia el campo de busqueda
        inputBusqueda.addEventListener('blur', (e) => {
            e.target.value = '';
        });

        // Consulta DB
        async function listadoCategorias() {
            try {

                const url = '/api/categorias/all';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                categoriasArray = resultado.categorias; // array de categorias
                categoriasArrayFiltrado = resultado.categorias;

                mostrarElementos();

            } catch (error) {
                console.log('No carga el listado' + error);
            }
        }

        function mostrarElementos() {

            // Elimina los elementos hijos
            limpiarElementos(contRegistros);
            limpiarElementos(contenedorVacio);
            

            if (categoriasArrayFiltrado.length === 0) {

                const textoNoCat = document.createElement('P');

                limpiarElementos(contenedorVacio);

                textoNoCat.textContent = "No se encontraron categorias";
                textoNoCat.classList.add('mensaje__vacio');
                contenedorVacio.appendChild(textoNoCat);
                return;
            }

            categoriasArrayFiltrado.forEach(categoria => {

                const contenedor = document.createElement('DIV');
                contenedor.classList.add('categoria__contenedor', 'swiper-slide');

                const catHeading = document.createElement('H3');
                catHeading.textContent = categoria.nombre;

                const catParrafo = document.createElement('P');
                catParrafo.classList.add('categoria__ganancia');
                catParrafo.textContent = "Ganancia: " + categoria.ganancia;

                const contenedorSM = document.createElement('DIV');
                contenedorSM.classList.add('formulario__contenedor-boton', 'formulario__contenedor-boton--sm');

                const catEnlace = document.createElement('A');
                catEnlace.setAttribute('href', `/categoria/categoria-edit/${categoria.id}`);
                catEnlace.classList.add('categoria__boton', 'categoria__boton--modificar');
                catEnlace.textContent = "Ver / Editar";

                const catBtn = document.createElement('BUTTON');
                catBtn.classList.add('categoria__boton', 'categoria__boton--eliminar');
                catBtn.textContent = "Eliminar";

                catBtn.addEventListener('click', async () => {
                    try {
                        await alertaDelete(categoria.id, 'categoria', true, tokenCSRF);
                        mostrarElementos();

                    } catch (error) {
                        console.log(error);
                    }
                });

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

        // Toma un id a eliminar, un tipo (categoria, provider o fabricante) que será parte de la url hacia la API
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
                        const resultado = await destroy(id, tipo, token);

                        if (resultado.eliminado) {
                            swalWithBootstrapButtons.fire(
                                'Eliminado/a',
                                helpers.firstCap(tipo) + ' ha sido destruido :(',
                                'success'
                            );
                            if (flag) {
                                categoriasArray = filtrarVirtualDOM(categoriasArray, id); // si hay un array va a filtrarlo
                                categoriasArrayFiltrado = categoriasArray;
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
                    );
                }
            });
        }

        // Toma un array (el vitualDOM) y un id a eliminar
        function filtrarVirtualDOM(array, id) {
            array = array.filter(element => element.id !== id);
            return array;
        }

        // Toma un id a eliminar y un tipo, este puede ser "categoria, provider o fabricante". El tipo es parte de la URL hacia la API
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

            categoriasArrayFiltrado = categoriasArrayFiltrado.filter(categoria => { // filtra elementos en memoria
                if (categoria.nombre.toLowerCase().search(Regex) !== -1) {
                    return categoria;
                }
            });

            // Recargar elementos
            mostrarElementos();
        }

    }
})();