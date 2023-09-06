(function() {

    console.log('Hola');

    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const btnCatPorc = document.querySelector('#btn-aumentos-cat');
    const categoria = document.querySelector('#aumentos-categoria');
    const categoriaPorc = document.querySelector('#aumentos-porc-cat');

    const btnProPorc = document.querySelector('#btn-aumentos-pro');
    const provider = document.querySelector('#aumentos-provider');
    const providerPorc = document.querySelector('#aumentos-porc-pro');

    const btnFabPorc = document.querySelector('#btn-aumentos-fab');
    const fabricante = document.querySelector('#aumentos-fabricantes');
    const fabricantePorc = document.querySelector('#aumentos-porc-fab');


    btnCatPorc.addEventListener('click', function() {

        // Al servidor envio categoria y porcentaje
        // El servidor devuelve la cantidad de registro afectados
        aumentoCategoria(categoria.value, categoriaPorc.value);

        categoria.value = '';
        categoriaPorc.value = '';

        console.log('Click!! Categoria!');
    });

    btnProPorc.addEventListener('click', function() {

        // Al servidor envio categoria y porcentaje
        // El servidor devuelve la cantidad de registro afectados
        aumentoCategoria(provider.value, providerPorc.value);

        provider.value = '';
        providerPorc.value = '';

        console.log('Click!! Provider!');
    });

    btnFabPorc.addEventListener('click', function() {

        // Al servidor envio categoria y porcentaje
        // El servidor devuelve la cantidad de registro afectados
        aumentoCategoria(fabricante.value, fabricantePorc.value);

        fabricante.value = '';
        fabricantePorc.value = '';

        console.log('Click!! Fabricante!');
    });


    async function aumentoCategoria(categoria, porcentaje) {

        try {
            const datos = new FormData();
            datos.append('categoria_id', categoria);
            datos.append('porcentaje', porcentaje)

            const url = '/api/aumentos/categoria';

            const respuesta = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenCSRF
                },
                body: datos
            });

            let resultado = await respuesta.json();

            console.log(resultado + "Han sido afectados");


            return resultado;

        } catch (error) {
            console.log('El servidor no responde');
        }
    }

    async function aumentoProvider(provider, porcentaje) {

        try {
            const datos = new FormData();
            datos.append('provider_id', provider);
            datos.append('porcentaje', porcentaje)

            const url = '/api/aumentos/provider';

            const respuesta = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenCSRF
                },
                body: datos
            });

            let resultado = await respuesta.json();

            console.log(resultado + "Han sido afectados");


            return resultado;

        } catch (error) {
            console.log('El servidor no responde');
        }
    }

    async function aumentoFabricante(categoria, porcentaje) {

        try {
            const datos = new FormData();
            datos.append('fabricante_id', fabricante);
            datos.append('porcentaje', porcentaje)

            const url = '/api/aumentos/fabricante';

            const respuesta = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenCSRF
                },
                body: datos
            });

            let resultado = await respuesta.json();

            console.log(resultado + "Han sido afectados");


            return resultado;

        } catch (error) {
            console.log('El servidor no responde');
        }
    }

})();