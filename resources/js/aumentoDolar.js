
// Consulto todos los productos y precios - productosDolar
// Asigno los elementos consultados en los array globales
// Recorro los array globales generando el código con scripting
// Al utilizar scripting tengo acceso a los eventos como el click sobre los botones
import swiper from './swiper';


(function () {

    if (document.querySelector('#btn-dolar')) {

        // Obtener elementos
        listadoDesactualizados();

        // Virtual DOM
        let productosArray = [];
        let preciosArray = [];


        const dolarInput = document.querySelector('#aumento-dolar');
        const btnDolar = document.querySelector('#btn-dolar');
        const contRegistros = document.querySelector('#aumento-dolar-registros'); // El DIV del paginador
        const btnActualizar = document.querySelector('#btn-dolar-actualizar');

        btnDolar.addEventListener('click', function () {
            // Busqueda con el boton
        });

        // Consultar todos los elementos
        async function listadoDesactualizados() {
            try {

                const url = '/api/aumentos/dolar-listado';

                const respuesta = await fetch(url);
                const resultado = await respuesta.json();

                productosArray = resultado.productos; // array de productos
                preciosArray = resultado.precios; // array de precios

                // <<< Mostrar Elementos
                mostrarElementos();


            } catch (error) {
                console.log('No carga el listado');
            }
        }

        function mostrarElementos() {

            // <<<< Limpiar elementos
            limpiarProductos();

            productosArray.forEach(producto => { // Cada producto
                preciosArray.forEach(precio => { // Cada precio
                    if (precio.id === producto.precio_id) {

                        producto.venta = redondear(producto.venta);

                        const cardProducto = document.createElement('DIV');
                        cardProducto.classList.add('swiper-slide');

                        // Formatear fecha (se obtiene tal cual esta almacenada en la DB)
                        const fechaObj = new Date(precio.updated_at);
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

                        if (producto.unidad_fraccion === null) {
                            producto.unidad_fraccion = '';
                        }

                        cardProducto.innerHTML = `                        
                        <div class=" producto__contenedor--slider ">

                            <a href="/producto/producto-show/${producto.id}">
                                <h3>${producto.nombre}</h3>
                            </a>
                            <p><span class=" font-bold">Código: </span>${producto.codigo.toUpperCase()}</p>
                            <p><span class=" font-bold">Dolar: </span>${precio.dolar}</p>
                            <p><span class=" font-bold">Costo sin IVA: $ </span>${precio.precio}</p>
                            <p><span class=" font-bold">Precio venta: $ </span>${producto.venta} <span class="font-bold">${producto.unidad_fraccion}</span></p>
                            <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                        </div>
                    
                    `;
                        contRegistros.appendChild(cardProducto);
                        swiper.update();
                    }

                }); // Fin cada precio
            }); // Fin cada producto
        }



        function limpiarProductos() {
            while (contRegistros.firstChild) {
                contRegistros.removeChild(contRegistros.firstChild);
            }
        }

        function redondear(numero) {
            return Math.ceil(numero / 10) * 10;
        }
    }

})();







