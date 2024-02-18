import * as helpers from './helpers';
(function () {

    if (document.querySelector('#dashboard__contenedor-tabs')) {

        const contenedorTabs = document.querySelector('#dashboard__contenedor-tabs');
        const tabs = document.querySelector('#dashboard__tabs');
        const contenedorPrincipal = document.querySelector('#buscador__contenedor-principal');
        const headingPrincipal = document.querySelector('.dashboard__heading');
        const tabsIcono = document.querySelector('.dashboard__tab-icono');

        // Virtual DOM
        let productosArray = {};
        let preciosArray = {};

        /* Paginación */
        // Página actual
        let page = 1;

        // Paginación
        let paginacion = '';

        // Encabezado tabla
        let tbody = '';

        /* Opciones de busqueda */
        const tabTodos = document.querySelector('#dashboard__tab-todos');
        const tabProrducto = document.querySelector('#dashboard__tab-producto');
        const tabCodigo = document.querySelector('#dashboard__tab-codigo');

        /* Buscador */
        let contenedorInput = '';
        let inputProductoFalso = '';
        let cardProducto = '';
        let tipoBusqueda = 'producto'; // Tab Actual

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado

        /* Filtros y Ordenamientos */
        let categoriaSeleccionada = '';
        let fabricanteSeleccionada = '';
        let providerSeleccionada = '';
        let terminoValue = '';
        let orden = "ASC";

        document.addEventListener('DOMContentLoaded', () => {

            tabs.classList.add('dashboard__tabs--activo');
            tabsIcono.classList.add('dashboard__tab-icono--activo');

            setTimeout(() => {
                tabs.classList.remove('dashboard__tabs--activo');
                tabsIcono.classList.remove('dashboard__tab-icono--activo');
            }, 5000);

            // Mostrar / Ocultar tabs
            contenedorTabs.addEventListener('mouseenter', () => {
                tabs.classList.add('dashboard__tabs--activo');
                tabsIcono.classList.add('dashboard__tab-icono--activo');

            });
            contenedorTabs.addEventListener('mouseleave', () => {
                tabs.classList.remove('dashboard__tabs--activo');
                tabsIcono.classList.remove('dashboard__tab-icono--activo');

            });

            // Buscador Principal / Producto
            generarBuscador();

            inputProductoFalso.addEventListener('click', function () {

                generarHTML();
            });

        });

        // Listeners a cada Tab
        tabCodigo.addEventListener('click', () => {

            enlaceBusquedaCodigo();

        });

        tabProrducto.addEventListener('click', () => {

            tipoBusqueda = "producto";
            headingPrincipal.textContent = "Buscador";

            // recargar archivo
            limpiarContenedor();
            generarBuscador();

            inputProductoFalso.addEventListener('click', function () {

                // insertar html
                generarHTML();
            });
        });

        tabTodos.addEventListener('click', async () => {

            // Eliminar contenido
            limpiarContenedor();

            headingPrincipal.textContent = "Todos los productos";

            // generar HTML filtros
            await generarFiltrosHTML();
            tbody = generarTabla();
            await renderizarRegistrosTabla();

        });

        function enlaceBusquedaCodigo() {
            // recargar archivo
            tipoBusqueda = "codigo";
            headingPrincipal.textContent = "Buscar código";

            limpiarContenedor();
            generarBuscador();
            busquedaCodigo();
        }

        function busquedaCodigo() {

            // Crear boton "busqueda"
            const btnBusqueda = document.createElement('BUTTON');
            btnBusqueda.classList.add('buscador__btn-busqueda');
            btnBusqueda.textContent = "Buscar";
            contenedorInput.appendChild(btnBusqueda);

            inputProductoFalso.placeholder = "Ingresa un código válido";
            contenedorInput.classList.add('buscador__input--no-valido');

            inputProductoFalso.addEventListener('input', function (e) {

                const regex = /^[a-zA-Z0-9]+$/;

                if (inputProductoFalso.value.length === 5 && regex.test(inputProductoFalso.value)) {

                    // cambiar la vista (color del box-shadow - verde)
                    btnBusqueda.classList.add('buscador__btn-busqueda--mostrar');
                    contenedorInput.classList.remove('buscador__input--no-valido');
                    contenedorInput.classList.add('buscador__input--valido');

                    inputProductoFalso.addEventListener('keydown', function (e) {

                        if (e.key === 'Enter' && inputProductoFalso.value.length === 5) {
                            // cuando el usuario presiona Enter hago la busqueda

                            const codigo = inputProductoFalso.value; // Los códigos estan escritos en minusculas
                            findDBCodigo(codigo.toLowerCase());

                        }
                    });

                    btnBusqueda.addEventListener('click', () => {

                        if (inputProductoFalso.value.length === 5 && regex.test(inputProductoFalso.value)) {

                            const codigo = inputProductoFalso.value; // Los códigos estan escritos en minusculas
                            findDBCodigo(codigo.toLowerCase());
                        }

                    });
                } else {
                    contenedorInput.classList.remove('buscador__input--valido');
                    contenedorInput.classList.add('buscador__input--no-valido');
                    btnBusqueda.classList.remove('buscador__btn-busqueda--mostrar');
                }
            });
        }

        async function findDBCodigo(codigo) {
            try {
                const datos = new FormData();
                datos.append('codigo_producto', codigo);

                const url = '/api/buscador/producto-codigo';
                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                const resultado = await respuesta.json();

                if (!resultado) {

                    while (cardProducto.lastChild) {
                        cardProducto.removeChild(cardProducto.lastChild);
                    }
                    cardProducto.classList.remove('producto__card-contenedor');

                    const mensajeSinResult = document.createElement('P');
                    mensajeSinResult.classList.add('mensaje__info');
                    mensajeSinResult.textContent = "El código no existe";

                    cardProducto.appendChild(mensajeSinResult);

                    inputProductoFalso.value = '';

                    return;
                }

                buscarProducto(resultado[0].id);

            } catch (error) {
                console.log('El servidor no responde');
            }
        }

        function generarBuscador() {

            contenedorInput = document.createElement('DIV');
            contenedorInput.classList.add('buscador__input', 'relative');

            const iconoBuscador = document.createElement('I');
            iconoBuscador.classList.add('buscador__icono-busqueda', 'fa-solid', 'fa-magnifying-glass');

            inputProductoFalso = document.createElement('INPUT');
            inputProductoFalso.classList.add('buscador__campo-busqueda');
            inputProductoFalso.type = 'text';
            inputProductoFalso.placeholder = 'Nombre del producto';

            cardProducto = document.createElement('DIV');

            contenedorInput.appendChild(iconoBuscador);
            contenedorInput.appendChild(inputProductoFalso);

            contenedorPrincipal.appendChild(contenedorInput);
            contenedorPrincipal.appendChild(cardProducto);

        }



        async function renderizarRegistrosTabla() {

            try {

                // Consultar todos los productos de la DB
                const resultado = await paginadorTodos();

                if (resultado.productos.length === 0 || resultado.precios.length === 0) {

                    sinResultados(); /// Mensaje "sin resultados"
                    return;

                } else {

                    // Generar table y thead

                    const tablaPaginacion = generarPaginacion();
                    // Renderizar productos paginados
                    recargarPaginacion(resultado, tbody, tablaPaginacion);
                }
            } catch (error) {
                console.log(error);
            }
        }

        async function generarFiltrosHTML() {

            // consultar DB
            const optsFiltros = await consultarCFP();

            const filtros = document.createElement('FORM');
            filtros.classList.add('buscador-listado');
            filtros.addEventListener('submit', (e) => {
                e.preventDefault();
            });

            const grid = document.createElement('DIV');
            grid.classList.add('buscador-listado__grid');

            const gridDos = document.createElement('DIV');
            gridDos.classList.add('buscador-listado__flex');

            /** Filtros */
            const selectCategoria = document.createElement('SELECT');
            selectCategoria.classList.add('buscador-listado__dropdown');
            selectCategoria.addEventListener('change', () => {

                categoriaSeleccionada = selectCategoria.value;
                page = 1; // reiniciar paginador
                orden = 'ASC';
                renderizarRegistrosTabla();

            });

            // Default
            const optCategoria = document.createElement('OPTION');
            optCategoria.textContent = "-- Seleccionar Categoria --";
            optCategoria.selected = true;
            optCategoria.value = '';

            selectCategoria.appendChild(optCategoria);

            // Cada categoria
            optsFiltros.categorias.forEach(cat => {
                const optCategoria = document.createElement('OPTION');
                optCategoria.value = cat.id;
                optCategoria.textContent = cat.nombre;

                selectCategoria.appendChild(optCategoria);
            });

            const selectFabricante = document.createElement('SELECT');
            selectFabricante.classList.add('buscador-listado__dropdown');
            selectFabricante.addEventListener('change', () => {

                fabricanteSeleccionada = selectFabricante.value;
                page = 1; // reiniciar paginador
                orden = 'ASC'; // reiniciar orden
                renderizarRegistrosTabla();

            });

            // Default
            const optFabricante = document.createElement('OPTION');
            optFabricante.textContent = "-- Seleccionar Fabricante --";
            optFabricante.selected = true;
            optFabricante.value = '';

            selectFabricante.appendChild(optFabricante);

            // Cada Fabricante
            optsFiltros.fabricantes.forEach(fab => {
                const optFabricante = document.createElement('OPTION');
                optFabricante.value = fab.id;
                optFabricante.textContent = fab.nombre;

                selectFabricante.appendChild(optFabricante);
            });

            const selectProveedor = document.createElement('SELECT');
            selectProveedor.classList.add('buscador-listado__dropdown');
            selectProveedor.addEventListener('change', () => {

                providerSeleccionada = selectProveedor.value;
                page = 1; // reiniciar paginador
                orden = 'ASC'; // reiniciar orden
                renderizarRegistrosTabla();

            });

            // Default
            const optProveedor = document.createElement('OPTION');
            optProveedor.textContent = "-- Seleccionar Proveedor --";
            optProveedor.selected = true;
            optProveedor.value = '';

            selectProveedor.appendChild(optProveedor);

            // Cada Proveedor
            optsFiltros.providers.forEach(fab => {
                const optProveedor = document.createElement('OPTION');
                optProveedor.value = fab.id;
                optProveedor.textContent = fab.nombre;

                selectProveedor.appendChild(optProveedor);
            });

            // Input Text, Buscador
            const buscadorFiltros = document.createElement('INPUT');
            buscadorFiltros.classList.add('buscador-listado__input');
            buscadorFiltros.placeholder = "Nombre del producto";
            buscadorFiltros.type = "text";

            buscadorFiltros.addEventListener('input', (e) => {

                if (e.target.value.length >= 3) {
                    page = 1; // reiniciar paginador
                    orden = 'ASC'; // reiniciar orden
                    terminoValue = e.target.value;
                    renderizarRegistrosTabla();
                } else {
                    page = 1; // reiniciar paginador
                    orden = 'ASC'; // reiniciar orden
                    terminoValue = '';
                    renderizarRegistrosTabla();
                }
            });

            const btnReset = document.createElement('BUTTON');
            btnReset.type = 'submit';
            btnReset.innerHTML = '<i class="fa-solid fa-rotate"></i>';
            btnReset.classList.add('formulario__boton', 'buscador-listado__btn-reset');

            /** Btn Reset */
            btnReset.addEventListener('click', (e) => {

                e.preventDefault();

                /* Reiniciar variables globales */
                page = 1;
                categoriaSeleccionada = '';
                fabricanteSeleccionada = '';
                providerSeleccionada = '';
                terminoValue = '';
                buscadorFiltros.value = '';
                selectCategoria.selectedIndex = 0;
                selectProveedor.selectedIndex = 0;
                selectFabricante.selectedIndex = 0;

                orden = 'ASC';
                renderizarRegistrosTabla();

            });

            gridDos.appendChild(buscadorFiltros);

            grid.appendChild(selectCategoria);
            grid.appendChild(selectFabricante);
            grid.appendChild(selectProveedor);
            grid.appendChild(btnReset);
            filtros.appendChild(grid);
            filtros.appendChild(gridDos);
            contenedorPrincipal.appendChild(filtros);
        }

        function generarPaginacion() {

            const tablaPaginacion = document.createElement('DIV');
            tablaPaginacion.id = 'tabla-buscador-paginacion';
            contenedorPrincipal.appendChild(tablaPaginacion);

            return tablaPaginacion;
        }

        function generarTabla() {

            const tabla = document.createElement('TABLE');
            tabla.classList.add('table');

            tabla.innerHTML = `
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th table__ocultar">Código</th>
                    <th scope="col" class="table__th pointer" id="filtros-orden-nombre">
                        Nombre
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th scope="col" class="table__th">Precio Venta</th>
                    <th scope="col" class="table__th table__ocultar">Categoria</th>
                    <th scope="col" class="table__th">Enlace</th>
                </tr>
            </thead>
            `;

            const tablaBody = document.createElement('TBODY');
            tablaBody.classList.add('table__tbody');

            tabla.appendChild(tablaBody);

            contenedorPrincipal.appendChild(tabla);
            contenedorPrincipal.classList.add('x-scroll');

            document.querySelector('#filtros-orden-nombre').addEventListener('click', () => {
                // cambiar orden

                if (orden === "ASC") {
                    orden = "DESC"
                } else {
                    orden = "ASC";
                }
                // hacer consulta y renderizar resultados
                page = 1; // reiniciar paginador
                renderizarRegistrosTabla();

            });
            return tablaBody;
        }

        async function consultarCFP() {

            // Consultar Categorias, Fabricantes y Providers
            try {
                const url = '/api/buscador/consultarCFP';

                const respuesta = await fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                });

                const resultado = await respuesta.json();
                return resultado;

            } catch (error) {
                console.log(error);
            }
        }

        async function paginadorTodos() {

            try {
                const url = '/api/buscador/todos';

                const datos = new FormData();
                datos.append('page', page);
                if (categoriaSeleccionada) datos.append('categoria', categoriaSeleccionada);
                if (fabricanteSeleccionada) datos.append('fabricante', fabricanteSeleccionada);
                if (providerSeleccionada) datos.append('provider', providerSeleccionada);
                if (terminoValue) datos.append('termino', terminoValue);
                if (orden) datos.append('orden', orden);

                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                const resultado = await respuesta.json();
                return resultado;

            } catch (error) {
                console.log(error);
            }
        }

        function recargarPaginacion(resultado, tbody, tablaPaginacion) {

            productosArray = resultado.productos;
            preciosArray = resultado.precios;
            paginacion = resultado.paginacion;

            // Generar elementos
            mostrarElementos(tbody, tablaPaginacion);
        }

        function mostrarElementos(tbody, tablaPaginacion) {

            limpiarTabla();

            productosArray.forEach(producto => { // Cada producto

                let iteracion = 0;
                preciosArray.forEach(precio => { // Cada precio

                    let unidadFraccion = '';
                    if (producto.unidad_fraccion) {
                        unidadFraccion = producto.unidad_fraccion;
                    }

                    let claseDescuento = '';
                    if (precio.desc_porc) {
                        claseDescuento = "c-red";
                    }

                    if (precio.id === producto.precio_id) {

                        if (iteracion >= 1) { // Evita duplicar registros en la tabla (los fraccionados iteran doble)
                            return;
                        }
                        iteracion++;

                        producto.venta = helpers.redondear(producto.venta);
                        tbody.innerHTML += `                        
                        <tr class="table__tr">
                            <td class="table__td table__ocultar">${producto.codigo.toUpperCase()}</td>
                            <td class="table__td">${producto.nombre}</td>
                            <td class="table__td ${claseDescuento}">$ ${producto.venta} ${unidadFraccion}</td>
                            <td class="table__td table__ocultar">${producto.categoria}</td>
                            <td class="table__td">
                                <a class="table__accion table__accion--editar" href="/producto/producto-show/${producto.id}">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;

                        if (paginacion !== '') {

                            tablaPaginacion.innerHTML = paginacion;

                            const enlaceNumero = document.querySelectorAll('[data-page]');
                            enlaceNumero.forEach(numero => {
                                numero.addEventListener('click', async (e) => {
                                    // Modificar page
                                    page = e.target.dataset.page;

                                    try {

                                        const resultado = await paginadorTodos();
                                        // Regenerar HTML
                                        recargarPaginacion(resultado, tbody, tablaPaginacion);
                                    } catch (error) {
                                        console.log(error);
                                    }
                                });
                            });

                            const enlaceBtn = document.querySelectorAll('[data-btn]');
                            enlaceBtn.forEach(boton => {
                                boton.addEventListener('click', async (e) => {

                                    try {

                                        if (e.target.dataset.btn === 'siguiente') {
                                            // regenerar HTML
                                            page++;
                                            renderizarRegistrosTabla();
                                            return;

                                        } else {
                                            // regenerar HTML
                                            page--;
                                            renderizarRegistrosTabla();
                                            return;
                                        }
                                    } catch (error) {
                                        console.log(error);
                                    }
                                });
                            });
                        }
                    }
                }); // Fin cada precio
            }); // Fin cada producto
        }

        function limpiarContenedor() {

            contenedorPrincipal.classList.remove('x-scroll');

            // Eliminar la barra de busqueda
            while (contenedorPrincipal.firstChild) {
                contenedorPrincipal.removeChild(contenedorPrincipal.firstChild);
            }
        }

        function limpiarTabla() {
            const paginacion = document.querySelector('#tabla-buscador-paginacion');
            tbody = document.querySelector('.table__tbody');

            // Eliminar contenido tabla
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Eliminar paginación
            while (paginacion.firstChild) {
                paginacion.removeChild(paginacion.firstChild);
            }
            if (document.querySelector('.mensaje__info')) document.querySelector('.mensaje__info').remove();
            if (document.querySelector('.paginacion')) document.querySelector('.paginacion').remove();
        }


        function sinResultados() {

            limpiarTabla();

            const mensajeNoResult = document.createElement('DIV');

            mensajeNoResult.innerHTML = `<p class="mensaje__info mb-4">
            No hay resultados
            </p>`;

            contenedorPrincipal.appendChild(mensajeNoResult);
        }

        // DOM scripting
        function generarHTML() {

            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor');

            const iconoBuscador = document.createElement('I');
            iconoBuscador.classList.add('buscador__icono-busqueda', 'fa-solid', 'fa-magnifying-glass');

            // input real
            const inputProducto = document.createElement('INPUT');
            inputProducto.type = 'text';
            inputProducto.name = 'producto-nombre';
            inputProducto.classList.add('buscador__campo', 'buscador__campo-focus');
            inputProducto.placeholder = 'Nombre del producto';

            if (inputProductoFalso.value) {
                inputProducto.value = inputProductoFalso.value;
            }

            const contenedorBusqueda = document.createElement('DIV');
            contenedorBusqueda.classList.add('buscador__opciones-contenedor--busqueda')

            contenedorBusqueda.appendChild(iconoBuscador);
            contenedorBusqueda.appendChild(inputProducto);
            contenedorOpciones.appendChild(contenedorBusqueda);

            // listado de coincidencias
            const lista = document.createElement('UL');

            contenedorOpciones.appendChild(lista);
            contenedorInput.appendChild(contenedorOpciones);

            inputProducto.focus(); // cursor sobre el input

            // realizar busqueda
            inputProducto.addEventListener('input', function (e) {

                if (flag === 1 && e.target.value.length < 3) { // Reinicio el flag para volver a realizar la busqueda
                    flag = 0;
                }

                filtrarResultado(e, lista, contenedorOpciones);
            });

            inputProducto.addEventListener('keyup', (e) => {

                if (e.key === 'Enter') {

                    if (coincidenciasPantalla[0]) {

                        buscarProducto(coincidenciasPantalla[0].id);

                        inputProductoFalso.value = coincidenciasPantalla[0].nombre;

                        eliminarCoincidencias(contenedorOpciones);

                    } else {

                        while (cardProducto.lastChild) {
                            cardProducto.removeChild(cardProducto.lastChild);
                        }
                        cardProducto.classList.remove('producto__card-contenedor');

                        const mensajeSinResult = document.createElement('P');
                        mensajeSinResult.classList.add('mensaje__info');

                        const enlaceCodigo = document.createElement('A');
                        enlaceCodigo.textContent = "Buscar por código";
                        enlaceCodigo.addEventListener('click', enlaceBusquedaCodigo);
                        enlaceCodigo.classList.add('enlace__mensaje');

                        // Este mensaje se completa con un enlace
                        mensajeSinResult.textContent = `
                        No hay resultados, deberías 
                        `;

                        mensajeSinResult.appendChild(enlaceCodigo);
                        cardProducto.appendChild(mensajeSinResult);
                    }
                }
            });

            contenedorInput.addEventListener('mouseleave', function () {

                inputProductoFalso.value = '';
                eliminarCoincidencias(contenedorOpciones);

            });
        }

        async function filtrarResultado(e, lista, contenedorOpciones) {
            try {
                if (!flag) {
                    arrayCoincidencias = await findDB(e.target.value); // Almaceno la respuesta en memoria
                }

            } catch (error) {
                console.log(error);
            }
            if (flag) { // filtrar el array en memoria

                buscarCoincidenciasMemoria(e, lista, contenedorOpciones);
            }
        }

        async function findDB(inputProducto) {

            if (inputProducto.length === 3) {
                try {
                    const datos = new FormData();
                    datos.append('input_producto', inputProducto);

                    const url = '/api/buscador/producto';
                    const respuesta = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                        body: datos
                    });

                    let resultado = await respuesta.json();
                    flag = 1;
                    return resultado;

                } catch (error) {
                    console.log('El servidor no responde');
                }
            }
        }

        function buscarCoincidenciasMemoria(e, lista, contenedorOpciones) {
            const busqueda = e.target.value;
            const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

            coincidenciasPantalla = arrayCoincidencias.filter(coincidencia => {
                if (coincidencia.nombre.toLowerCase().search(Regex) !== -1) {
                    return coincidencia;
                }
            });

            generarHTMLcoincidencia(lista, contenedorOpciones)
        }

        function generarHTMLcoincidencia(lista, contenedorOpciones) {

            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }

            let acu = 0; // Cantidad de coincidencias

            coincidenciasPantalla.forEach(coincidencia => {

                acu++;
                if (acu <= 6) { // Cantidad de coincidencias en pantalla

                    const sugerenciaBusqueda = document.createElement('LI');
                    sugerenciaBusqueda.textContent = coincidencia.nombre;

                    lista.appendChild(sugerenciaBusqueda);
                    contenedorOpciones.classList.add('buscador__opciones-contenedor--activo');

                    sugerenciaBusqueda.addEventListener('click', function (e) {

                        buscarProducto(coincidencia.id);

                        coincidenciasPantalla[0] = coincidencia;
                        inputProductoFalso.value = coincidencia.nombre;

                        eliminarCoincidencias(contenedorOpciones);
                    });
                }
            });
        }

        function eliminarCoincidencias(contenedorOpciones) {

            // eliminar html
            while (contenedorOpciones.firstChild) {
                contenedorOpciones.removeChild(contenedorOpciones.firstChild);
            }
            contenedorOpciones.remove();

            productosArray = {};
            preciosArray = {};

        }

        async function buscarProducto(id) {

            try {
                const datos = new FormData();
                datos.append('id', id);

                const url = '/api/buscador/producto-individual';
                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCSRF
                    },
                    body: datos
                });

                let resultado = await respuesta.json();

                resultado.producto.venta = helpers.redondear(resultado.producto.venta);

                // Formatear fecha (se obtiene tal cual esta almacenada en la DB)
                const fechaObj = new Date(resultado.precio.updated_at);
                const mes = fechaObj.getMonth();
                const dia = fechaObj.getDate() + 1; // Corrijo desfasaje
                const year = fechaObj.getFullYear();

                const fechaUTC = new Date(Date.UTC(year, mes, dia));

                const opciones = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }
                const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);
                const precioFormateado = helpers.formatearDineroSinDecimales(resultado.producto.venta);
                const costoFormateado = helpers.formatearDineroSinDecimales(resultado.precio.precio);

                if (resultado.producto.unidad_fraccion === null) {
                    resultado.producto.unidad_fraccion = '';
                }

                cardProducto.classList.add('producto__card-contenedor');

                if (resultado.precio.desc_porc) {
                    // Producto en oferta
                    cardProducto.innerHTML = `
                        <div class=" producto__contenedor producto__contenedor--descuento ">
                            <a href="/producto/producto-show/${resultado.producto.id}">
                                <h3 class="producto__card-nombre">${resultado.producto.nombre} - <span class="c-red">En Oferta</span></h3>
                            </a>
                            <div class="producto__grid-card">
                                <div class="producto__card-info">
                                    <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                                    <p><span class="c-red font-bold">Descuento: </span>${resultado.precio.desc_porc} % </p>
                                    <p><span class="c-red font-bold">Descuento finaliza en: </span>${resultado.precio.semanas_restantes} semanas</p>
                                    <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                                    <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                                    <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                                </div>
                                <div class="producto__contenedor-precio">
                                    <p class="producto__card-precio producto__card-precio--oferta">$ ${resultado.producto.venta}<span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                                    <div class="producto__btn-opts">
                                        <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Editar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;

                } else {

                    // No tiene descuento 
                    cardProducto.innerHTML = `
                    <div class=" producto__contenedor ">
                        <a href="/producto/producto-show/${resultado.producto.id}">
                            <h3 class="producto__card-nombre">${resultado.producto.nombre}</h3>
                        </a>
                        <div class="producto__grid-card">


                            <div class="producto__card-info">
                                <p class=" font-bold">Código: <span class=" font-regular" >${resultado.producto.codigo.toUpperCase()}</span></p>
                                <p class=" font-bold">Ganancia aplicada: <span class=" font-regular" >${resultado.producto.ganancia}</span></p>
                                <p class=" font-bold">Costo sin IVA: <span class=" font-regular" >${costoFormateado}</span></p>
                                <p class=" font-bold">Stock Restante: <span class=" font-regular" id="producto-card-stock-actual">${resultado.producto.stock - 1}</span></p>
                                <p class=" font-bold">Modificación: <span class=" font-regular" >${fechaFormateada}</span></p>
                            </div>
                            <div class="producto__contenedor-precio">
                                <p class="producto__card-precio">${precioFormateado}</span><span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                                <div class="producto__btn-opts">

                                    <div class="producto__btn-venta">
                                    
                                        <button class="producto__numero" id="producto-card-venta-submit">
                                            Vender
                                            <p id="producto-card-venta-numero">1</p>
                                        </button>
                                        <div class="producto__btns-arrows">
                                        
                                            <i class="fa-solid fa-square-caret-up" id="producto-card-venta-up"></i>
                                            <i class="fa-solid fa-square-caret-down" id="producto-card-venta-down"></i>
                                        </div>
                                    </div>
                                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Editar</a>
                                </div>
                            </div>
                        </div>
                    </div
                `;
                }

                if (document.querySelector('#producto-card-venta-up') && document.querySelector('#producto-card-venta-down')) {

                    const stockOriginal = Number(document.querySelector('#producto-card-stock-actual').textContent);
                    let cantidad = Number(document.querySelector('#producto-card-venta-numero').textContent);
                    let stock = Number(document.querySelector('#producto-card-stock-actual').textContent);

                    document.querySelector('#producto-card-venta-submit').addEventListener('click', () => {

                        // enviar request a la DB
                        console.log('click');
                        informarVenta(cantidad, stock, resultado.producto.id);
                    });

                    function informarVenta(cantidadVendida, restarStock, id) {
                        // En la DB se almacena el ingreso total, el precio de venta restando el costo
                        // la cantidad sirve para multiplicar el precio de venta
                        // el stock puede ser diferente a la cantidad si el usuario no tiene el stock al dia
                    }


                    document.querySelector('#producto-card-venta-up').addEventListener('click', () => {
                        

                        if (cantidad < 99) {
                            cantidad++;
                            document.querySelector('#producto-card-venta-numero').textContent = cantidad;
                            if (stock >= 1) {
                                stock--;
                                document.querySelector('#producto-card-stock-actual').textContent = stock;
                            }
                        }
                        if(stock === 0) {
                            document.querySelector('#producto-card-stock-actual').classList.add('c-red');
                        } else {
                            document.querySelector('#producto-card-stock-actual').classList.remove('c-red');

                        }

                    });
                    document.querySelector('#producto-card-venta-down').addEventListener('click', () => {


                        if (cantidad > 1) {
                            cantidad--;
                            document.querySelector('#producto-card-venta-numero').textContent = cantidad;
                            if (stock < stockOriginal && cantidad <= stockOriginal) {
                                stock++;
                                document.querySelector('#producto-card-stock-actual').textContent = stock;
                            }
                        }
                        if(stock === 0) {
                            document.querySelector('#producto-card-stock-actual').classList.add('c-red');
                        } else {
                            document.querySelector('#producto-card-stock-actual').classList.remove('c-red');

                        }

                    });
                }

            } catch (error) {
                console.log('El servidor no responde' + error);
            }
        }
    }
})();