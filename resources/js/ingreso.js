import * as helpers from './helpers';

(function () {

    if (document.querySelector('#mercaderia-grid')) {


        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const grid = document.querySelector('#mercaderia-grid');
        let flag = 0; // Saber cuando se obtuvo el primer resultado de la DB
        let arrayCoincidencias = []; // Aqui se almacena el resultado de la DB
        let coincidenciasPantalla = []; // Aqui se almacena el resultado de la DB filtrado
        let contForms = 0;


        document.addEventListener('DOMContentLoaded', app);

        function app () {
            if(contForms > 30) {
                window.location.reload();
            }
            if(contForms > 29) {
                alert('Último producto, la página será actualizada.');
            }

            const obj = generarForm();
            
            const { checkbox, cantidad, codigo, nombre, precio, descuento, semanas, btnGuardar, btnEliminar } = obj;
            
            nombre.addEventListener('click', (e) => {
                generarHTML(e, obj);
                
            });
            codigo.addEventListener('click', (e) => {
                generarHTML(e);
                
            });
            btnGuardar.addEventListener('click', (e) => {
                
                desactivarCampos(obj);
                btnGuardar.disabled = true;

                // Validar datos
                validarCampos(obj);

                // Almacenar informacion

                app();
                
            });
            btnEliminar.addEventListener('click', (e) => {
                
                vaciarCampos(obj);
                
            });
            
            // Añadir funcionalidad Código: Busca el código y autocompleta todos los campos, no genera HTML. El código existe o no
            
            // Añadfir funcionalidad Guardar: Almacena la información, bloquea los campos, cambia boton ,crea un nuevo formulario
            
            contForms++;
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
        }


        function generarForm() {

            const contenedorCheck = document.createElement('DIV');
            contenedorCheck.classList.add('formulario__contenedor-checkbox');

            const checkbox = document.createElement('INPUT');
            checkbox.type = 'checkbox';
            checkbox.classList.add('formulario__checkbox');

            contenedorCheck.appendChild(checkbox);

            const cantidad = document.createElement('INPUT');
            cantidad.type = 'number';
            cantidad.classList.add('formulario__campo', 'height-full');
            cantidad.placeholder = "0";


            const contenedorCodigo = document.createElement('DIV');
            contenedorCodigo.classList.add('relative');

            const codigo = document.createElement('INPUT');
            codigo.type = 'text';
            codigo.classList.add('formulario__campo', 'height-full');
            codigo.placeholder = "Código";

            contenedorCodigo.appendChild(codigo);

            const contenedorNombre = document.createElement('DIV');
            contenedorNombre.classList.add('relative');

            const nombre = document.createElement('INPUT');
            nombre.type = 'text';
            nombre.classList.add('formulario__campo', 'height-full');
            nombre.placeholder = "Nombre del producto";

            contenedorNombre.appendChild(nombre);

            const precio = document.createElement('INPUT');
            precio.type = 'number';
            precio.classList.add('formulario__campo');
            precio.placeholder = "Precio sin IVA";

            const descuento = document.createElement('INPUT');
            descuento.type = 'number';
            descuento.classList.add('formulario__campo');
            descuento.placeholder = "0%";

            const semanas = document.createElement('SELECT');
            semanas.classList.add('formulario__campo');

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

            grid.appendChild(contenedorCheck);
            grid.appendChild(cantidad);
            grid.appendChild(contenedorCodigo);
            grid.appendChild(contenedorNombre);
            grid.appendChild(precio);
            grid.appendChild(descuento);
            grid.appendChild(semanas);
            grid.appendChild(btnGuardar);
            grid.appendChild(btnEliminar);

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


        //////////
        function generarHTML(e, obj) {

            // Contenedor
            const contenedorOpciones = document.createElement('DIV');
            contenedorOpciones.classList.add('buscador__opciones-contenedor');

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

                if (flag === 1 && e.target.value.length < 3) { // Reinicio el flag para volver a realizar la busqueda
                    flag = 0;
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


        /// <<<< Separar

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

        /// <<< Separar

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

        //// <<< Separar

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


        //// <<< Separar

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

                        const respuesta = await buscarProducto(coincidencia.id);
                        
                        const { checkbox, codigo, nombre, precio, descuento, semanas } = obj;


                        codigo.value = respuesta.producto.codigo.toUpperCase();

                        nombre.value = respuesta.producto.nombre;

                        // Bloquear nombre y codigo una vez cargados, el usuario solo puede modificar el precio y descuentos


                        inputProducto.value = respuesta.producto.nombre;

                        precio.value = respuesta.precio.precio;
                        if(!respuesta.precio.descuento) {
                            descuento.value = 0;
                        } else {
                            descuento.value = respuesta.precio.descuento;
                        }
                        if(!respuesta.precio.semanas) {
                            descuento.value = 0;
                        } else {
                            descuento.value = respuesta.precio.semanas;
                        }
                        
                    });
                }
            });
        }


        //// <<< Separar

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



                // inputProductoFalso.value = '';
                // inputCodigo.value = '';

            } catch (error) {
                console.log('El servidor no responde');
            }
        }


        /////////






    }
})();