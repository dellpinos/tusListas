(function () {

    if (document.querySelector('#btn-dolar')) {



        const dolarInput = document.querySelector('#aumento-dolar');
        const btnDolar = document.querySelector('#btn-dolar');
        const contRegistros = document.querySelector('#aumento-dolar-registros');
        const btnActualizar = document.querySelector('#btn-dolar-actualizar');


        document.addEventListener('DOMContentLoaded', function () {
            // Debo consultar los productos mas desactualizados (20u) 


            listadoDesactualizados();

        });


        btnDolar.addEventListener('click', function () {

        });



        async function listadoDesactualizados() {

            try {



                const url = '/api/aumentos/dolar-listado';

                const respuesta = await fetch(url);

                const resultado = await respuesta.json();



                const productos = resultado.productos;
                const precios = resultado.precios;

                productos.forEach(producto => { // Cada producto

                    precios.forEach(precio => { // Cada precio
                        if (precio.id === producto.precio_id) {

                            producto.venta = redondear(producto.venta);




                            const cardProducto = document.createElement('DIV');



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
                        <a href="/producto/producto-show/${producto.id}" class="producto__grid-card">
                            <div class=" producto__contenedor ">
                                <p><span class=" font-bold">Código: </span>${producto.codigo.toUpperCase()}</p>
                                <p><span class=" font-bold">Producto: </span>${producto.nombre}</p>
                                <p><span class=" font-bold">Dolar: </span>${precio.dolar}</p>
                                <p><span class=" font-bold">Costo sin IVA: $ </span>${precio.precio}</p>
                                <p><span class=" font-bold">Precio venta: $ </span>${producto.venta} <span class="font-bold">${producto.unidad_fraccion}</span></p>
                                <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                            </div>
                            <a href="/producto/producto-edit/${producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
                        </a>
                        `;

                            contRegistros.appendChild(cardProducto);




                        }

                    }); // Fin cada precio

                }); // Fin cada producto




            } catch (error) {
                console.log('No carga el listado');
            }
        }

        function redondear(numero) {
            return Math.ceil(numero / 10) * 10;
        }
    }
})();
