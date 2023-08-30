(function () {

    // Listener al contenedor de los <input> radio
    const gananciaPersonalizada = document.querySelector('#ganancia-personalizada');
    const campoPersonalizado = document.querySelector('#ganancia');
    const contenedorRadios = document.querySelector('#contenedor-radios');
    const campoSinIva = document.querySelector('#precio');
    const campoConIva = document.querySelector('#precio-iva');
    const btnVenta = document.querySelector('#btn-venta');
    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const campoVenta = document.querySelector('#precio-venta');



    document.addEventListener("DOMContentLoaded", function () {
        if (campoConIva.value !== undefined) {
            if(campoSinIva.value !== '') {
                campoConIva.value = Math.round(campoSinIva.value * 1.21);

            }
        }
    });

    contenedorRadios.addEventListener('click', function () {


            habilitarCampo();
    });

    campoSinIva.addEventListener('input', function () {
        campoConIva.value = Math.round(campoSinIva.value * 1.21);
    });

    campoConIva.addEventListener('input', function () {
        campoSinIva.value = Math.round(campoConIva.value / 1.21);
    });

    // Consultar precio venta
    btnVenta.addEventListener('click', function () {
        calcularGanancia();

    });


    // Habilitar / Deshabilitar campo opcional
    function habilitarCampo() {
        if (campoPersonalizado.disabled === true && gananciaPersonalizada.checked === true) {
            campoPersonalizado.disabled = false;
            campoPersonalizado.classList.remove('formulario__campo--no-activo');
        
        } else { // Este campo se deshabilita aunque presionen "pesonalizado"
            campoPersonalizado.disabled = true;
            campoPersonalizado.classList.add('formulario__campo--no-activo');
            campoPersonalizado.value = '';

        }
    }


    async function calcularGanancia() {

        const radioChecked = document.querySelector('input[type="radio"]:checked');
        let precioVenta = 0;


        if (radioChecked.value === 'personalizada') {
            // Calculo leyendo el formulario
            precioVenta = (campoSinIva.value * 1.21) * campoPersonalizado.value;

        } else if (radioChecked.value === 'proveedor') {

            // Consulta la DB
            const proveedor_id = document.querySelector('#proveedor');
            ganancia = await consultarGanancia(radioChecked.value, proveedor_id.value);
            precioVenta = (campoSinIva.value * 1.21) * ganancia;

        } else {
            const categoria_id = document.querySelector('#categoria');
            ganancia = await consultarGanancia(radioChecked.value, categoria_id.value);
            precioVenta = (campoSinIva.value * 1.21) * ganancia;

        }
        campoVenta.value = precioVenta;
    }


    async function consultarGanancia(seleccion, id) {

        try {
            const datos = new FormData();
            datos.append('ganancia', seleccion);
            datos.append('id', id)

            const url = '/api/calculo/ganancia';

            const respuesta = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenCSRF
                },
                body: datos
            });

            let resultado = await respuesta.json();
            campoPersonalizado.value = resultado;
            return resultado;

        } catch (error) {
            console.log('El servidor no responde');
        }
    }


})();