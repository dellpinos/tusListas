(function () {

    if (document.querySelector('#precio')) {

        const campoPersonalizado = document.querySelector('#ganancia');
        const campoSinIva = document.querySelector('#precio');
        const campoConIva = document.querySelector('#precio-iva');
        const btnVenta = document.querySelector('#btn-venta'); // calcular precio venta
        const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const campoVenta = document.querySelector('#precio-venta');

        const checkFraccion = document.querySelector('#check-fraccion'); // abre formulario secundario
        const contenedorOculto = document.querySelector('#producto-contenedor-oculto');
        let codigoFraccionado = document.querySelector('#codigo-fraccionado');
        const unidadFraccion = document.querySelector('#unidad-fraccion');
        const totalFraccionado = document.querySelector('#contenido-total');
        const gananciaFraccion = document.querySelector('#ganancia-fraccion');
        const precioFraccionado = document.querySelector('#precio-fraccionado');
        const btnFraccionado = document.querySelector('#btn-fraccionado'); // calcular precio fraccionado

        const radiobtns = document.querySelectorAll('input[type="radio"]');
        let radioChecked = document.querySelector('input[type="radio"]:checked');
        let precioVenta = 0;
        const click = true;

        const selectCat = document.querySelector('#categoria');
        const selectProv = document.querySelector('#provider');


        radiobtns.forEach(btn => {
            btn.addEventListener('click', (e) => {

                habilitarCampo(e);

                calcularGanancia();
                campoVenta.value = '';
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (campoConIva.value !== undefined) {
                if (campoSinIva.value !== '') {
                    campoConIva.value = Math.round(campoSinIva.value * 1.21);

                }

                calcularGanancia();
            }
        });

        campoSinIva.addEventListener('input', function () {

            precioFraccionado.value = 0;
            campoVenta.value = 0;
            calcularGanancia(click);
            campoConIva.value = Math.round(campoSinIva.value * 1.21);
        });

        campoConIva.addEventListener('input', function () {
            campoSinIva.value = Math.round(campoConIva.value / 1.21);

        });

        // Consultar precio venta
        btnVenta.addEventListener('click', function () {

            calcularGanancia(click);

        });

        // Habilitar / Deshabilitar campo opcional
        function habilitarCampo(e) {


            if (e.target.value === 'personalizada' && campoPersonalizado.readOnly === true) {

                campoPersonalizado.readOnly = false;
                campoPersonalizado.classList.remove('formulario__campo--no-activo');
            } else if (e.target.value !== 'personalizada') {

                campoPersonalizado.readOnly = true;
                campoPersonalizado.classList.add('formulario__campo--no-activo');
                campoPersonalizado.value = '';
            }
        }

        async function calcularGanancia(click) {


            if(selectCat.value !== '' && selectProv.value !== '') { // Debe escoger categoria y provider primero

                radioChecked = document.querySelector('input[type="radio"]:checked');

                console.log(typeof(radioChecked.value) + " " + radioChecked.value); /// << funciona bien

                if (radioChecked.value === 'personalizada') {
                    // Calculo leyendo el formulario
                    precioVenta = (campoSinIva.value * 1.21) * campoPersonalizado.value;
    
                } else if (radioChecked.value === 'provider') {
    
                    // Consulta la DB
                    const provider_id = document.querySelector('#provider');

                    console.log(provider_id.value + " provider id");
                    let ganancia = await consultarGanancia(radioChecked.value, provider_id.value);
                    console.log(ganancia + " ganancia provider");
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;
                    console.log(precioVenta + " precio venta provider");
    
                } else if (radioChecked.value === 'categoria') {
    
                    const categoria_id = document.querySelector('#categoria');
                    console.log(categoria_id.value + " categoria id");
                    let ganancia = await consultarGanancia(radioChecked.value, categoria_id.value);
                    console.log(ganancia + " ganancia");
                    precioVenta = (campoSinIva.value * 1.21) * ganancia;
                    console.log(precioVenta + " precio venta");
    
                }
    
                if (click) { // Solo cambio el "precio venta" si es presionado el btn de calcular
                    campoVenta.value = redondear(precioVenta);
                }
    
                if (checkFraccion) {
    
                    if (checkFraccion.checked === true) {
                        checkFraccion.checked = false;
                        deseleccionarFraccionado();
    
                    }
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

        if (checkFraccion) {

            checkFraccion.addEventListener('click', function () {

                if (checkFraccion.checked === true) {
                    // Seleccionado

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
                precioFraccionado.value = redondear((precioVenta / totalFraccionado.value) * gananciaFraccion.value);
            } else {
                console.log('Debes calcular el precio No Fraccionado primero');
            }
        }

        function deseleccionarFraccionado() {

            contenedorOculto.classList.add('producto-formulario__contenedor-oculto');
            contenedorOculto.classList.remove('producto-formulario__contenedor-visible');

            // Vaciar campos
            unidadFraccion.required = false;
            totalFraccionado.required = false;
            gananciaFraccion.required = false;

            precioFraccionado.value = '';
            unidadFraccion.value = null; // Cambio de '' a null
            totalFraccionado.value = null;
            gananciaFraccion.value = null;
            codigoFraccionado = null;

        }
        function redondear(numero) {
            return Math.ceil(numero / 10) * 10;
        }
    }
})();