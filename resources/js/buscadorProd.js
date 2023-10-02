import * as helpers from './helpers';
(function () {

    if (document.querySelector('#dashboard__contenedor-tabs')) {

        const contenedorTabs = document.querySelector('#dashboard__contenedor-tabs');
        const tabs = document.querySelector('#dashboard__tabs');
        const contenedorPrincipal = document.querySelector('#buscador__contenedor-principal');
        const headingPrincipal = document.querySelector('.dashboard__heading');

        /* Paginación */
        // Virtual DOM
        let productosArray = {};
        let preciosArray = {};

        // pagina actual
        let page = 1;

        // paginacion
        let paginacion = '';

        /* Opciones de busqueda */
        const tabTodos = document.querySelector('#dashboard__tab-todos');
        const tabProrducto = document.querySelector('#dashboard__tab-producto');
        const tabCodigo = document.querySelector('#dashboard__tab-codigo');
        const tabCategoria = document.querySelector('#dashboard__tab-categoria');
        const tabFabricante = document.querySelector('#dashboard__tab-fabricante');
        const tabProvider = document.querySelector('#dashboard__tab-provider');

        let tipoBusqueda = 'producto';

        /* Buscador */
        let contenedorInput = '';
        let inputProductoFalso = '';
        let cardProducto = '';
        let contenedorSecundario = '';

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputCodigo = document.querySelector('#producto-codigo');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado

        document.addEventListener('DOMContentLoaded', () => {

            tabs.classList.add('dashboard__tabs--activo');

            setTimeout(() => {
                tabs.classList.remove('dashboard__tabs--activo');
            }, 5000);

            // Mostrar / Ocultar tabs
            contenedorTabs.addEventListener('mouseenter', () => {
                tabs.classList.add('dashboard__tabs--activo');
            });
            contenedorTabs.addEventListener('mouseleave', () => {
                tabs.classList.remove('dashboard__tabs--activo');
            });

            // Buscador Producto
            generarBuscador();

            inputProductoFalso.addEventListener('click', function () {

                // insertar html
                generarHTML();
            });

        });


        // let flagExisteBarra = // La barra se elimina cuando el usuario lista "todos los productos", cuando presione otra
        // opcion de busqueda esta barra debe volver a generarse. Utilizo esta variable para comprobar si existe actualmente la barra o si debo generarla


        // El usuario presiona en un tab y esto modifica la variable "tipoBusqueda", modifica el placeholder
        // El listener del click dentro del campo de busqueda llama a "generarHTML" a menos que la busqueda sea por codigo o todos
        // Todos elimina la barra de busqueda y la reemplaza por la paginacion de todos los registros
        // Codigo hace una busqueda pero sin renderizar "coincidencias" - solo responde "existe" o "no existe"


        /////////// Buscador por codigo



        tabCodigo.addEventListener('click', () => {

            // recargar archivo
            tipoBusqueda = "codigo";
            headingPrincipal.textContent = "Buscar código";

            limpiarContenedor();

            generarBuscador();

            busquedaCodigo();

            // Cambiar el buscador
            // Cambiar placeholder y almacenar un "flag" para el momento en que el usuario presione en el input falso



            console.log('Buscar por Código');
        });


        // Buscar por codigo - producto se cambian con un paginador
        // El usuario puede escoger uno u otro metodo de busqueda


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



                if (inputProductoFalso.value.length === 4 && regex.test(inputProductoFalso.value)) {

                    // cambiar la vista (color del box-shadow - verde)
                    btnBusqueda.classList.add('buscador__btn-busqueda--mostrar');
                    contenedorInput.classList.remove('buscador__input--no-valido');
                    contenedorInput.classList.add('buscador__input--valido');

                    console.log('valido para busqueda, apreta enter');

                    inputProductoFalso.addEventListener('keydown', function (e) {

                        console.log(inputProductoFalso.value.length);

                        if (e.key === 'Enter' && inputProductoFalso.value.length === 4) {
                            // cuando el usuario presiona Enter hago la busqueda

                            const codigo = inputProductoFalso.value; // Los códigos estan escritos en minusculas
                            findDBCodigo(codigo.toLowerCase());

                        }

                    });


                    btnBusqueda.addEventListener('click', () => {

                        if (inputProductoFalso.value.length === 4 && regex.test(inputProductoFalso.value)) {

                            const codigo = inputProductoFalso.value; // Los códigos estan escritos en minusculas
                            findDBCodigo(codigo.toLowerCase());
                        }

                        // buscar
                    });
                } else {
                    contenedorInput.classList.remove('buscador__input--valido');
                    contenedorInput.classList.add('buscador__input--no-valido');

                    btnBusqueda.classList.remove('buscador__btn-busqueda--mostrar');
                }
            });
        }
        // cambiar la vista (color del box-shadow - rojo)


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




        /////////


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

        tabTodos.addEventListener('click', async () => {

            // Eliminar contenido
            limpiarContenedor();

            headingPrincipal.textContent = "Todos los productos";

            // Consultar todos los productos de la DB
            const resultado = await paginadorTodos();

            if (resultado.productos.length === 0 || resultado.precios.length === 0) {

                sinResultados(); /// Mensaje "sin resultados"
                return;

            } else {

                // Generar table y thead
                const tbody = generarTabla();
                const tablaPaginacion = generarPaginacion();
                // Renderizar productos paginados
                recargarPaginacion(resultado, tbody, tablaPaginacion);
            }
        });

        function generarPaginacion() {

            const tablaPaginacion = document.createElement('DIV');
            contenedorPrincipal.appendChild(tablaPaginacion);

            return tablaPaginacion;
        }

        function generarTabla() {

            const tabla = document.createElement('TABLE');
            tabla.classList.add('table');
            tabla.innerHTML = `
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Código</th>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Precio Costo</th>
                    <th scope="col" class="table__th">Precio Venta</th>
                    <th scope="col" class="table__th">Enlace</th>
                </tr>
            </thead>
            `;

            const tablaBody = document.createElement('TBODY');
            tablaBody.classList.add('table__tbody');

            tabla.appendChild(tablaBody);

            contenedorPrincipal.appendChild(tabla);

            return tablaBody;

        }

        async function paginadorTodos() {

            try {
                const url = '/api/buscador/todos';

                const datos = new FormData();
                datos.append('page', page);

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

            limpiarTabla(tbody, tablaPaginacion);

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
                        <td class="table__td">${producto.codigo.toUpperCase()}</td>
                        <td class="table__td">${producto.nombre}</td>
                        <td class="table__td">$ ${precio.precio}</td>
                        <td class="table__td ${claseDescuento}">$ ${producto.venta} ${unidadFraccion}</td>
                        <td class="table__td"><a class="table__accion table__accion--editar" href="/producto/producto-show/${producto.id}">Editar</a></td>
                        </tr>
                    `;

                        if (paginacion !== '') {

                            tablaPaginacion.innerHTML = paginacion;

                            const enlaceNumero = document.querySelectorAll('[data-page]');
                            enlaceNumero.forEach(numero => {
                                numero.addEventListener('click', async (e) => {
                                    // modificar page
                                    page = e.target.dataset.page;
                                    const resultado = await paginadorTodos();
                                    recargarPaginacion(resultado, tbody, tablaPaginacion);
                                    // regenerar HTML
                                });
                            });

                            const enlaceBtn = document.querySelectorAll('[data-btn]');
                            enlaceBtn.forEach(boton => {
                                boton.addEventListener('click', async (e) => {

                                    if (e.target.dataset.btn === 'siguiente') {
                                        // regenerar HTML
                                        page++;
                                        const resultado = await paginadorTodos();
                                        recargarPaginacion(resultado, tbody, tablaPaginacion);

                                        return;

                                    } else {
                                        // regenerar HTML
                                        page--;
                                        const resultado = await paginadorTodos();
                                        recargarPaginacion(resultado, tbody, tablaPaginacion);
                                        return;
                                    }
                                });
                            });
                        }
                    }
                }); // Fin cada precio
            }); // Fin cada producto
        }


        function limpiarContenedor() {

            // Eliminar la barra de busqueda
            while (contenedorPrincipal.firstChild) {
                contenedorPrincipal.removeChild(contenedorPrincipal.firstChild);
            }
        }

        function limpiarTabla(tbody, paginacion = '') {
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }
            if (paginacion) {
                while (paginacion.firstChild) {
                    paginacion.removeChild(paginacion.firstChild);
                }
            }

        }
        function sinResultados() {

            limpiarContenedor();

            const mensajeNoResult = document.createElement('DIV');

            mensajeNoResult.innerHTML = `<p class="mensaje__info--my">
            No hay productos, deberias crear el primero
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
                        mensajeSinResult.textContent = "No hay resultados";

                        cardProducto.appendChild(mensajeSinResult);
                    }

                }
            });

            contenedorInput.addEventListener('mouseleave', function () {

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
            if (flag) { // aqui puedo filtrar el array en memoria

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
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }
                const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);

                if (resultado.producto.unidad_fraccion === null) {
                    resultado.producto.unidad_fraccion = '';
                }

                cardProducto.classList.add('producto__card-contenedor');

                if (resultado.precio.desc_porc) {
                    // Producto en oferta
                    cardProducto.innerHTML = `
                    <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                    <div class=" producto__contenedor producto__contenedor--descuento ">
                        <h3 class="producto__card-nombre">${resultado.producto.nombre} - <span class="c-red">En Oferta</span></h3>
                        <div class="producto__contenedor-precio">
                            <p class="producto__card-precio producto__card-precio--oferta">$ ${resultado.producto.venta}<span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                        </div>
                        <div class="producto__card-info">
                            <p><span class=" font-bold">Descuento: </span>${resultado.precio.desc_porc} % </p>
                            <p><span class=" font-bold">Descuento finaliza en: </span>${resultado.precio.semanas_restantes} semanas</p>
                            <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                            <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                            <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                            <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                        </div>
        
                    </div>
                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
                </a>
                `;

                } else {

                    // No tiene descuento
                    cardProducto.innerHTML = `
                    <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                    <div class=" producto__contenedor ">
                        <h3 class="producto__card-nombre">${resultado.producto.nombre}</h3>
                        <div class="producto__contenedor-precio">
                            <p class="producto__card-precio">$ ${resultado.producto.venta}<span class="font-bold"> ${resultado.producto.unidad_fraccion}</span></p>
                        </div>
                        <div class="producto__card-info">
                            <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                            <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                            <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                            <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                        </div>
        
                    </div>
                    <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
                </a>
                `;
                }

            } catch (error) {
                console.log('El servidor no responde' + error);
            }
        }
    }

})();