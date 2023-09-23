(function () {

    if (document.querySelector('#producto-codigo')) {

        const inputCodigo = document.querySelector('#producto-codigo');
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const btnCodigo = document.querySelector('#btn-codigo');
        const cardProducto = document.querySelector('#card-producto');
        const inputProductoFalso = document.querySelector('#producto-nombre-falso');

        inputCodigo.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                if (inputCodigo.value.length >= 4) {

                    let codigo = inputCodigo.value; // Los códigos estan escritos en minusculas
                    findDB(codigo.toLowerCase());
                }
            }
        });

        btnCodigo.addEventListener('click', function (e) {

            if (inputCodigo.value.length >= 4) {

                let codigo = inputCodigo.value; // Los códigos estan escritos en minusculas
                findDB(codigo.toLowerCase());
            }
        });

        async function findDB(codigo) {
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

                    // El código no existe
                    cardProducto.innerHTML = `
                    <div class=" producto__contenedor ">
                        <p><span class=" font-bold">El código no existe</p>
                    </div>
                `;

                    inputProductoFalso.value = '';
                    inputCodigo.value = '';

                    return;
                }

                buscarProducto(resultado[0].id);

            } catch (error) {
                console.log('El servidor no responde');
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

                resultado.producto.venta = redondear(resultado.producto.venta);

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

                cardProducto.innerHTML = `
            <a href="/producto/producto-show/${resultado.producto.id}" class="producto__grid-card">
                <div class=" producto__contenedor ">
                    <p><span class=" font-bold">Código: </span>${resultado.producto.codigo.toUpperCase()}</p>
                    <p><span class=" font-bold">Producto: </span>${resultado.producto.nombre}</p>
                    <p><span class=" font-bold">Ganancia aplicada: </span>${resultado.producto.ganancia}</p>
                    <p><span class=" font-bold">Costo sin IVA: $ </span>${resultado.precio.precio}</p>
                    <p><span class=" font-bold">Precio venta: $ </span>${resultado.producto.venta} <span class="font-bold">${resultado.producto.unidad_fraccion}</span></p>
                    <p><span class=" font-bold">Modificación: </span>${fechaFormateada}</p>
                </div>
                <a href="/producto/producto-edit/${resultado.producto.id}" class="producto__card-contenedor-boton producto__boton producto__boton--verde">Modificar</a>
            </a>
            `;
                inputProductoFalso.value = '';
                inputCodigo.value = '';

            } catch (error) {
                console.log('El servidor no responde');
            }
        }
        function redondear(numero) {
            return Math.ceil(numero / 10) * 10;
        }

    }

})();