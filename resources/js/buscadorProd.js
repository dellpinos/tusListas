(function () {

    const contenedorOpciones = document.querySelector('#contenedor-buscador-opciones');
    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inputProductoFalso = document.querySelector('#producto-nombre-falso');
    const inputProducto = document.querySelector('#producto-nombre');

    inputProductoFalso.addEventListener('click', function() {
        contenedorOpciones.classList.toggle('display-none');
    });
    contenedorOpciones.addEventListener('mouseleave', function() {
        contenedorOpciones.classList.toggle('display-none');
    })


    inputProducto.addEventListener('input', function(e) {

        if (e.target.value.length >= 3) {
            findDB(e.target.value);
        }
    });

    async function findDB(inputProducto) {

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

            const resultado = await respuesta.json();

            console.log(resultado);
        }

        // El input contenedor debe crearse al hacer click en el input falso, junto al input y el <ul>
        //luego
    // Escojo/recorto los primeros 5 registros del resultado e itero el array para crear cada <li>
    





})();