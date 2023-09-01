(function () {

    const gananciaPersonalizada = document.querySelector('#ganancia-personalizada');
    const campoPersonalizado = document.querySelector('#ganancia');
    const contenedorRadios = document.querySelector('#contenedor-radios');
    const campoSinIva = document.querySelector('#precio');
    const campoConIva = document.querySelector('#precio-iva');
    const btnVenta = document.querySelector('#btn-venta');
    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const campoVenta = document.querySelector('#precio-venta');

    const checkFraccion = document.querySelector('#check-fraccion');
    const contenedorOculto = document.querySelector('#producto-contenedor-oculto');
    let codigoFraccionado = document.querySelector('#codigo-fraccionado');
    const unidadFraccion = document.querySelector('#unidad-fraccion');
    const totalFraccionado = document.querySelector('#contenido-total');
    const gananciaFraccion = document.querySelector('#ganancia-fraccion');
    const precioFraccionado = document.querySelector('#precio-fraccionado');
    const btnFraccionado = document.querySelector('#btn-fraccionado');

    let precioVenta = 0;

    document.addEventListener("DOMContentLoaded", function () {
        if (campoConIva.value !== undefined) {
            if (campoSinIva.value !== '') {
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
        campoVenta.value = redondear(precioVenta);

        if(checkFraccion) {

            if (checkFraccion.checked === true){
                checkFraccion.checked = false;
                deseleccionarFraccionado();
    
            }
        }

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

    btnFraccionado.addEventListener('click', calcularGananciaFraccionado);

    if(checkFraccion) {

        checkFraccion.addEventListener('click', function () {

            if (checkFraccion.checked === true) {
                // Seleccionado
                console.log('visible!');
    
                contenedorOculto.classList.add('producto-formulario__contenedor-visible');
                contenedorOculto.classList.remove('producto-formulario__contenedor-oculto');
    
                // Consultar DB para obtener código
                if (codigoFraccionado.value === '') {
                    (async () => {
                        const codigo = await generarCodigo();
                        codigoFraccionado.value = codigo.toUpperCase(); // Nuevo código
                    })();
                }
    
                // Añadir required al HTML
                unidadFraccion.required = true;
                totalFraccionado.required = true;
                gananciaFraccion.required = true;
    
                // Generar código
                async function generarCodigo() {
    
                    try {
                        const url = '/api/codigo-unico';
                        const respuesta = await fetch(url);
                        const resultado = await respuesta.json();
    
                        return resultado;
    
                    } catch (error) {
                        console.log(error);
                    }
                }
            } else {
                // Deseleccionado
                deseleccionarFraccionado();
            }
        });
    }
    
    async function calcularGananciaFraccionado() {

        if (precioVenta) {
            // Calculo leyendo el formulario
            precioFraccionado.value = redondear(Math.round((precioVenta / totalFraccionado.value) * gananciaFraccion.value));
        } else {
            console.log('Debes calcular el precio No Fraccionado primero');
        }
    }

    function deseleccionarFraccionado(){
        console.log('invisible!');
        contenedorOculto.classList.add('producto-formulario__contenedor-oculto');
        contenedorOculto.classList.remove('producto-formulario__contenedor-visible');

        // Vaciar campos
        unidadFraccion.required = false;
        totalFraccionado.required = false;
        gananciaFraccion.required = false;

        unidadFraccion.value = '';
        totalFraccionado.value = '';
        gananciaFraccion.value = '';
        precioFraccionado.value = '';
        codigoFraccionado = '';

    }
        function redondear(numero) {
        return Math.ceil(numero / 10) * 10;
    }
})();