import * as helpers from './helpers';

(function () {

    if (document.querySelector('#mercaderia-grid')) {

        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const grid = document.querySelector('#mercaderia-grid');
        const dolarPrevio = document.querySelector('.ingreso__numero');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let flagIVA = false; // False -> sin IVA / True -> con IVA
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado
        let precioId = ''; // Precio seleccionado que será almacenado
        let productoId = ''; // Producto seleccionado que será almacenado
        let contForms = 0;

        document.addEventListener('DOMContentLoaded', app);

        function app() {

            if (contForms > 100) {
                window.location.reload();
            }
            if (contForms > 99) {
                alert('Último producto, la página será actualizada.');
            }

            const obj = generarForm();
            const { cantidad, codigo, nombre, precio, descuento, semanas, btnGuardar, btnEliminar } = obj;

            nombre.addEventListener('click', (e) => {
                generarHTML(e, obj);

            });

            codigo.addEventListener('input', async (e) => {

                try {

                    const resultado = await findCodigo(codigo.value);

                    if (resultado) {

                        const resultadoCompleto = await buscarProducto(resultado[0].id);
                        completarCampos(resultadoCompleto.producto, resultadoCompleto.precio, obj);

                        precioId = resultadoCompleto.precio.id; // Precio seleccionado que será almacenado
                        productoId = resultadoCompleto.producto.id; // Producto seleccionado que será almacenado
                    }

                } catch (error) {
                    console.log(error);
                }
            });

            btnGuardar.addEventListener('click', async (e) => {

                // Validar datos
                const objV = validarCampos(obj);

                if (!objV.validado) {
                    return;
                } else {
                    descuento.classList.remove('b-red', 'b-green');
                    semanas.classList.remove('b-red', 'b-green');
                    codigo.classList.remove('b-red', 'b-green');
                    nombre.classList.remove('b-red', 'b-green');
                    precio.classList.remove('b-red', 'b-green');
                    cantidad.classList.remove('b-red', 'b-green');
                }

                if(flagIVA) {
                    objV.precio = Math.round(objV.precio / 1.21);

                }
                
                // Almacenar informacion
                const resultado = await almacenarDatos(objV);

                if (resultado) {

                    // Limpiar variables en memoria
                    precioId = '';
                    productoId = '';
                    flagIVA = false;
                    desactivarCampos(obj);
                    app();

                    await alertPendiente();

                }
            });

            btnEliminar.addEventListener('click', (e) => {
                // Vaciar campos
                vaciarCampos(obj);
                // Limpiar variables en memoria
                precioId = '';
                productoId = '';

            });

            contForms++;
        }

        async function almacenarDatos(objV) {

            const { cantidad, precio, descuento, semanas, pendiente, nombre, dolar } = objV;

            if (pendiente) {

                // Es pendiente, se almacena en "pendientes"
                try {
                    const datos = new FormData();
                    datos.append('nombre', nombre);
                    datos.append('cantidad', cantidad);
                    datos.append('precio', precio);
                    datos.append('descuento', descuento);
                    datos.append('semanas', semanas);
                    datos.append('dolar', dolar);

                    const url = '/api/pendientes/create';
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
                    console.log('El servidor no responde' + error);
                }


            } else {
                // No es pendiente, se almacena en "productos"
                try {
                    const datos = new FormData();
                    datos.append('producto_id', productoId); // global
                    datos.append('cantidad', cantidad);
                    datos.append('precio', precio);
                    datos.append('descuento', descuento);
                    datos.append('semanas', semanas);
                    datos.append('dolar', dolar);

                    const url = '/api/productos/update';
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
        }

        function validarCampos(obj) {

            const { checkbox, cantidad, codigo, nombre, precio, descuento, semanas, btnGuardar, btnEliminar } = obj;
            let objV = {};

            let flagValidacion = [];
            let flagValidacionGral = true;
            const regexCodigo = /^[a-zA-Z0-9]{5}$/;
            const regexDescuento = /^[0-9]{1,3}(\.[0-9]{1,3})?$/;
            const regexPrecio = /^[0-9]+(\.[0-9]+)?$/;
            const regexCantidad = /^(0|[1-9]\d*)$/;

            descuento.classList.remove('b-red', 'b-green');
            semanas.classList.remove('b-red', 'b-green');
            codigo.classList.remove('b-red', 'b-green');
            nombre.classList.remove('b-red', 'b-green');
            precio.classList.remove('b-red', 'b-green');
            cantidad.classList.remove('b-red', 'b-green');

            if ((descuento.value !== '' && semanas.value === '0') || (descuento.value === '' && semanas.value !== '0') ||
                (descuento.value === '0' && semanas.value === '0') || (descuento.value === '0' && semanas.value !== '0') ||
                (descuento.value !== '' && !regexDescuento.test(descuento.value))) {
                descuento.classList.add('b-red');
                semanas.classList.add('b-red');
                descuento.classList.remove('b-green');
                semanas.classList.remove('b-green');
                flagValidacion[0] = false;
            } else {
                descuento.classList.remove('b-red');
                semanas.classList.remove('b-red');
                descuento.classList.add('b-green');
                semanas.classList.add('b-green');
                flagValidacion[0] = true;
            }

            if (cantidad.value !== '' && !regexCantidad.test(cantidad.value) || cantidad.value.length > 6) {
                cantidad.classList.add('b-red');
                flagValidacion[4] = false;
            } else {
                cantidad.classList.add('b-green');
                flagValidacion[4] = true;
            }

            if (checkbox.checked) {

                objV = {
                    pendiente: true
                }
                // Es pendiente
                if (codigo.value !== '') {
                    // El código debe estar vacio
                    codigo.classList.add('b-red');
                    flagValidacion[1] = false;
                } else {
                    codigo.classList.add('b-green');
                    flagValidacion[1] = true;
                }
                if (nombre.value === '') {
                    // Nombre no puede estar vacio y debe ser un string
                    nombre.classList.add('b-red');
                    flagValidacion[2] = false;
                } else {
                    nombre.classList.add('b-green');
                    flagValidacion[2] = true;
                }
                if (precio.value === '' || !regexPrecio.test(precio.value)) {
                    // Precio no puede estar vacio y debe ser un numero
                    precio.classList.add('b-red');
                    flagValidacion[3] = false;
                } else {
                    precio.classList.add('b-green');
                    flagValidacion[3] = true;
                }

            } else {

                objV = {
                    pendiente: false
                }
                if (productoId === '' || precioId === '') {
                    flagValidacion[5] = false;
                    codigo.classList.add('b-red');
                } else {
                    flagValidacion[5] = true;
                    codigo.classList.add('b-green');
                }
                // No es pendiente
                if (codigo.value === '' || !regexCodigo.test(codigo.value)) {
                    // El código debe estar vacio
                    codigo.classList.add('b-red');
                    flagValidacion[1] = false;
                } else {
                    codigo.classList.add('b-green');
                    flagValidacion[1] = true;
                }
                if (nombre.value === '') {
                    // Nombre no puede estar vacio y debe ser un string
                    nombre.classList.add('b-red');
                    flagValidacion[2] = false;
                } else {
                    nombre.classList.add('b-green');
                    flagValidacion[2] = true;
                }
                if (precio.value === '' || !regexPrecio.test(precio.value)) {
                    // Precio no puede estar vacio y debe ser un numero
                    precio.classList.add('b-red');
                    flagValidacion[3] = false;
                } else {
                    precio.classList.add('b-green');
                    flagValidacion[3] = true;
                }
            }

            flagValidacion.forEach(e => {
                if (e === false) {
                    flagValidacionGral = false;
                }
            });

            if (flagValidacionGral) {

                // Sanitaizando datos que serán almacenados
                precioId = parseInt(precioId);
                productoId = parseInt(productoId);
                let cantidadDB = '';
                let descuentoDB = '';
                const pendiente = objV.pendiente;

                let dolar = parseInt(dolarPrevio.textContent);

                // Reemplazo NaN por null
                if (isNaN(parseInt(cantidad.value))) {
                    cantidadDB = '';
                } else {
                    cantidadDB = parseInt(cantidad.value);
                }
                if (isNaN(parseInt(descuento.value))) {
                    descuentoDB = '';
                } else {
                    descuentoDB = parseInt(descuento.value);
                }

                objV = {
                    dolar: dolar,
                    nombre: nombre.value,
                    pendiente: pendiente,
                    cantidad: cantidadDB,
                    precio: parseFloat(precio.value),
                    descuento: descuentoDB,
                    semanas: parseInt(semanas.value),
                    validado: true,
                }

                return objV;

            } else {
                objV = {
                    validado: false
                }
                return objV;
            }
        }
        function desactivarCampos(obj) {

            const { checkbox, cantidad, codigo, nombre, precio, descuento, semanas, btnGuardar, btnEliminar } = obj;

            btnEliminar.innerHTML = `
            <i class="fa-solid fa-circle-check"></i>
            `;
            btnEliminar.classList.remove('ingreso__icono--delete');
            btnGuardar.classList.remove('ingreso__icono--delete');
            btnEliminar.classList.add('ingreso__icono--confirm');
            btnGuardar.classList.add('ingreso__icono--confirm');
            btnGuardar.innerHTML = `
            <i class="fa-solid fa-circle-check"></i>
            `;

            btnEliminar.disabled = true;
            btnGuardar.disabled = true;

            checkbox.disabled = true;
            cantidad.disabled = true;
            codigo.disabled = true;
            nombre.disabled = true;
            precio.disabled = true;
            descuento.disabled = true;
            semanas.disabled = true;

            checkbox.classList.add('no-pointer');
            semanas.classList.add('no-pointer');
        }
        function vaciarCampos(obj) {
            const { checkbox, cantidad, codigo, nombre, precio, descuento, semanas, btnGuardar, btnEliminar } = obj;

            checkbox.checked = false;
            cantidad.value = '';
            codigo.value = '';
            nombre.value = '';
            precio.value = '';
            descuento.value = '';
            semanas.value = 0;

            descuento.classList.remove('b-red', 'b-green');
            semanas.classList.remove('b-red', 'b-green');
            codigo.classList.remove('b-red', 'b-green');
            nombre.classList.remove('b-red', 'b-green');
            precio.classList.remove('b-red', 'b-green');
            descuento.classList.remove('b-red', 'b-green');
            semanas.classList.remove('b-red', 'b-green');
        }
        function generarForm() {

            /// crear un label para cada campon e inhabilitarlo con display:none / habilitarlo en mq:phone a la vez que deshabilito los que se encuentran en el HTML


            // Añado contenedor y Label para la versión móvil
            const contenedorLabelCheckbox = document.createElement('DIV');
            contenedorLabelCheckbox.classList.add('ingreso__contenedor-campo-movil');

            const labelCheckboxMovil = document.createElement('LABEL');
            labelCheckboxMovil.classList.add('ingreso__label-movil');
            labelCheckboxMovil.textContent = "Pendiente";

            contenedorLabelCheckbox.appendChild(labelCheckboxMovil);

            const contenedorCheck = document.createElement('DIV');
            contenedorCheck.classList.add('formulario__contenedor-checkbox');

            const checkbox = document.createElement('INPUT');
            checkbox.type = 'checkbox';
            checkbox.classList.add('formulario__checkbox');

            contenedorCheck.appendChild(checkbox);
            contenedorLabelCheckbox.appendChild(contenedorCheck);


            const contenedorLabelCantidad = document.createElement('DIV');
            contenedorLabelCantidad.classList.add('ingreso__contenedor-campo-movil');

            const labelCantidadMovil = document.createElement('LABEL');
            labelCantidadMovil.classList.add('ingreso__label-movil');
            labelCantidadMovil.textContent = "Cantidad";

            contenedorLabelCantidad.appendChild(labelCantidadMovil);

            const cantidad = document.createElement('INPUT');
            cantidad.type = 'text';
            cantidad.classList.add('formulario__campo', 'ingreso__campo');
            cantidad.placeholder = "0";

            contenedorLabelCantidad.appendChild(cantidad);

            const contenedorLabelCodigo = document.createElement('DIV');
            contenedorLabelCodigo.classList.add('ingreso__contenedor-campo-movil');

            const labelCodigoMovil = document.createElement('LABEL');
            labelCodigoMovil.classList.add('ingreso__label-movil');
            labelCodigoMovil.textContent = "Código";

            contenedorLabelCodigo.appendChild(labelCodigoMovil);

            const contenedorCodigo = document.createElement('DIV');
            contenedorCodigo.classList.add('relative', 'ingreso__campo');

            const codigo = document.createElement('INPUT');
            codigo.type = 'text';
            codigo.classList.add('formulario__campo');
            codigo.placeholder = "Código";

            contenedorCodigo.appendChild(codigo);
            contenedorLabelCodigo.appendChild(contenedorCodigo);

            const contenedorLabelNombre = document.createElement('DIV');
            contenedorLabelNombre.classList.add('ingreso__contenedor-campo-movil');

            const labelNombreMovil = document.createElement('LABEL');
            labelNombreMovil.classList.add('ingreso__label-movil');
            labelNombreMovil.textContent = "Nombre";

            contenedorLabelNombre.appendChild(labelNombreMovil);

            const contenedorNombre = document.createElement('DIV');
            contenedorNombre.classList.add('relative', 'ingreso__campo');

            const nombre = document.createElement('INPUT');
            nombre.type = 'text';
            nombre.classList.add('formulario__campo');
            nombre.placeholder = "Nombre del producto";

            contenedorNombre.appendChild(nombre);
            contenedorLabelNombre.appendChild(contenedorNombre);

            const contenedorLabelPrecio = document.createElement('DIV');
            contenedorLabelPrecio.classList.add('ingreso__contenedor-campo-movil');

            const labelMovilPrecio = document.createElement('LABEL');
            labelMovilPrecio.classList.add('ingreso__label-movil');
            labelMovilPrecio.textContent = "Precio sin IVA";

            contenedorLabelPrecio.appendChild(labelMovilPrecio);

            const contenedorPrecio = document.createElement('DIV');
            contenedorPrecio.classList.add('ingreso__contenedor-precio', 'ingreso__campo');

            const contenedorCheckIVA = document.createElement('DIV');
            contenedorCheckIVA.classList.add('ingreso__contenedor-checkboxIVA');

            const precio = document.createElement('INPUT');
            precio.type = 'text';
            precio.classList.add('formulario__campo', 'ingreso__campo-precio');
            precio.placeholder = "Precio sin IVA";

            const optIVA = document.createElement('P');
            optIVA.textContent = "sin IVA";
            optIVA.classList.add('ingreso__checkbox-IVA');

            contenedorPrecio.appendChild(precio);
            contenedorCheckIVA.appendChild(optIVA);
            contenedorPrecio.appendChild(contenedorCheckIVA);

            contenedorLabelPrecio.appendChild(contenedorPrecio);

            contenedorCheckIVA.addEventListener('click', () => {

                if(!flagIVA) {
                    // Con IVA

                    flagIVA = true;
                    optIVA.classList.add('ingreso__checkbox-IVA--checked');
                    precio.placeholder = "Precio con IVA";
                    optIVA.textContent = "con IVA";
                    labelMovilPrecio.textContent = "Precio con IVA";
                    precio.classList.add('ingreso__campo-precio--checked');
                } else {
                    // Sin IVA

                    flagIVA = false;
                    precio.classList.remove('ingreso__campo-precio--checked');
                    optIVA.classList.remove('ingreso__checkbox-IVA--checked');
                    precio.placeholder = "Precio sin IVA";
                    optIVA.textContent = "sin IVA";
                    labelMovilPrecio.textContent = "Precio sin IVA";
                }
            });

            const contenedorLabelDescuento = document.createElement('DIV');
            contenedorLabelDescuento.classList.add('ingreso__contenedor-campo-movil');

            const labelDescuentoMovil = document.createElement('LABEL');
            labelDescuentoMovil.classList.add('ingreso__label-movil');
            labelDescuentoMovil.textContent = "% Descuento";

            contenedorLabelDescuento.appendChild(labelDescuentoMovil);

            const descuento = document.createElement('INPUT');
            descuento.type = 'text';
            descuento.classList.add('formulario__campo', 'ingreso__campo');
            descuento.placeholder = "0 %";

            contenedorLabelDescuento.appendChild(descuento);

            const contenedorLabelSemanas = document.createElement('DIV');
            contenedorLabelSemanas.classList.add('ingreso__contenedor-campo-movil');

            const labelSemanasMovil = document.createElement('LABEL');
            labelSemanasMovil.classList.add('ingreso__label-movil');
            labelSemanasMovil.textContent = "Semanas";

            contenedorLabelSemanas.appendChild(labelSemanasMovil);

            const semanas = document.createElement('SELECT');
            semanas.classList.add('formulario__campo', 'ingreso__campo');

            contenedorLabelSemanas.appendChild(semanas);

            for (let i = 0; i <= 8; i++) {

                const option = document.createElement('OPTION');
                option.value = i;
                option.textContent = i;

                if (i === 0) {
                    option.selected = true;
                }
                semanas.appendChild(option);
            }

            const btnGuardar = document.createElement('BUTTON');
            btnGuardar.classList.add('boton', 'ingreso__icono', 'ingreso__icono--save');
            btnGuardar.innerHTML = `
            <i class="fa-solid fa-floppy-disk"></i>
            `;

            const btnEliminar = document.createElement('BUTTON');
            btnEliminar.classList.add('boton', 'ingreso__icono', 'ingreso__icono--delete');
            btnEliminar.innerHTML = `
            <i class="fa-solid fa-trash"></i>
            `;

            const contenedorBtns = document.createElement('DIV');
            contenedorBtns.classList.add('ingreso__contenedor-btns');

            contenedorBtns.appendChild(btnGuardar);
            contenedorBtns.appendChild(btnEliminar);

            grid.appendChild(contenedorLabelCheckbox);
            grid.appendChild(contenedorLabelCantidad);
            grid.appendChild(contenedorLabelCodigo);
            grid.appendChild(contenedorLabelNombre);
            grid.appendChild(contenedorLabelPrecio);
            grid.appendChild(contenedorLabelDescuento);
            grid.appendChild(contenedorLabelSemanas);
            grid.appendChild(contenedorBtns);

            const obj = {
                checkbox: checkbox,
                cantidad: cantidad,
                codigo: codigo,
                nombre: nombre,
                precio: precio,
                descuento: descuento,
                semanas: semanas,
                btnGuardar: btnGuardar,
                btnEliminar: btnEliminar
            }
            return obj;
        };
        function generarHTML(e, obj) {

            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor', 'ingreso__opciones-contenedor');

            // input real
            const inputProducto = document.createElement('INPUT');
            inputProducto.type = 'text';
            inputProducto.classList.add('buscador__campo', 'buscador__campo-focus');

            if (e.target.value !== '') {
                inputProducto.value = e.target.value;
            }
            contenedorOpciones.appendChild(inputProducto);

            // listado de coincidencias
            const lista = document.createElement('UL');

            contenedorOpciones.appendChild(lista);
            e.target.parentNode.appendChild(contenedorOpciones);

            inputProducto.focus(); // cursor sobre el input

            // realizar busqueda
            inputProducto.addEventListener('input', async function (element) {

                if (flag === 1 && element.target.value.length < 3) { // Reinicio el flag para volver a realizar la busqueda
                    flag = 0;

                    // Limpiar VirtualDOM
                    arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
                    coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado
                }
                filtrarResultado(element, lista, obj, inputProducto);
            });

            e.target.parentNode.addEventListener('mouseleave', function () { /// sobreescribe el nombre

                e.target.value = inputProducto.value;
                // eliminar html
                while (contenedorOpciones.firstChild) {
                    contenedorOpciones.removeChild(contenedorOpciones.firstChild);
                }
                contenedorOpciones.remove();
            });
        }
        async function filtrarResultado(element, lista, obj, inputProducto) {
            try {
                if (!flag) {
                    arrayCoincidencias = await findDB(element.target.value); // Almaceno la respuesta en memoria
                }
            } catch (error) {
                console.log(error);
            }
            if (flag) { // aqui puedo filtrar el array en memoria

                const resultado = buscarCoincidenciasMemoria(element, lista, obj, inputProducto);
                return resultado;
            }
        }
        async function findDB(inputProducto) {

            if (inputProducto.length === 3) {
                try {
                    const datos = new FormData();
                    datos.append('filtro_frac', true);
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
        async function findCodigo(codigo) {

            if (codigo.length === 5) {
                try {
                    const datos = new FormData();
                    datos.append('filtro_frac', true);
                    datos.append('codigo_producto', codigo);

                    const url = '/api/buscador/producto-codigo';
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
        }
        function buscarCoincidenciasMemoria(element, lista, obj, inputProducto) {
            const busqueda = element.target.value;
            const Regex = new RegExp(busqueda, 'i'); // la "i" es para ser insensible a mayusculas/minusculas

            coincidenciasPantalla = arrayCoincidencias.filter(coincidencia => {
                if (coincidencia.nombre.toLowerCase().search(Regex) !== -1) {
                    return coincidencia;
                }
            });
            generarHTMLcoincidencia(lista, obj, inputProducto);
        }
        function generarHTMLcoincidencia(lista, obj, inputProducto) {

            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }

            let acu = 0; // Cantidad de coincidencias
            coincidenciasPantalla.forEach(coincidencia => {

                acu++;
                if (acu <= 4) {
                    const sugerenciaBusqueda = document.createElement('LI');
                    sugerenciaBusqueda.textContent = coincidencia.nombre;
                    lista.appendChild(sugerenciaBusqueda);

                    sugerenciaBusqueda.addEventListener('click', async function (e) {

                        try {

                            const respuesta = await buscarProducto(coincidencia.id);
                            const DBproducto = respuesta.producto;
                            const DBprecio = respuesta.precio;

                            precioId = respuesta.precio.id; // Precio seleccionado que será almacenado
                            productoId = respuesta.producto.id; // Producto seleccionado que será almacenado

                            completarCampos(DBproducto, DBprecio, obj, inputProducto);
                        } catch (error) {
                            console.log(error);
                        }

                    });
                }
            });
        }

        function completarCampos(DBproducto, DBprecio, obj, inputProducto) {

            const { checkbox, codigo, nombre, precio, descuento, semanas } = obj;

            codigo.value = DBproducto.codigo.toUpperCase();

            if (inputProducto) {
                inputProducto.value = DBproducto.nombre;
            } else {
                nombre.value = DBproducto.nombre;
            }

            precio.value = DBprecio.precio;

            if (!DBprecio.desc_porc) {
                descuento.value = '';
            } else {
                descuento.value = DBprecio.desc_porc;
            }
            if (!DBprecio.desc_duracion) {
                semanas.value = 0;
            } else {
                semanas.value = DBprecio.desc_duracion;
            }

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
                return resultado;

            } catch (error) {
                console.log('El servidor no responde' + error);
            }
        }

        async function alertPendiente() {

            try {

                const resultado = await consultaPendientes();

                if (resultado > 0) {

                    if (!document.querySelector('#sidebar-pendiente-alert')) {

                        const iconoProducto = document.querySelector('#sidebar-new-prod');
                        const notif = document.createElement('I');

                        notif.classList.add('sidebar__alert-pendiente', 'fa-solid', 'fa-circle-exclamation');
                        notif.id = 'sidebar-pendiente-alert';

                        iconoProducto.appendChild(notif);
                    }

                } else {

                    if (document.querySelector('#sidebar-pendiente-alert')) {
                        const notif = document.querySelector('#sidebar-pendiente-alert');
                        notif.remove();
                    }
                }

            } catch (error) {
                console.log(error);
            }
        }

        async function consultaPendientes() {

            try {

                const url = '/api/pendientes/count';

                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                return resultado;

            } catch (error) {
                console.log(error)
            }
        }
    }
})();