(function(){

    // Listener al contenedor de los <input> radio
    const gananciaPersonalizada = document.querySelector('#ganancia-personalizada');
    const campoPersonalizado = document.querySelector('#ganancia');
    const contenedorRadios = document.querySelector('#contenedor-radios');

    contenedorRadios.addEventListener('click', function(){
        habilitarCampo();
    });

    // Habilitar / Deshabilitar campo opcional
    function habilitarCampo() {
        if(campoPersonalizado.disabled === true && gananciaPersonalizada.checked === true) {
            campoPersonalizado.disabled = false;
            campoPersonalizado.classList.remove('text-gray-500', 'bg-gray-300', 'cursor-not-allowed');

        } else {
            campoPersonalizado.disabled = true;
            campoPersonalizado.classList.add('text-gray-500', 'bg-gray-300', 'cursor-not-allowed');
            campoPersonalizado.value = '';
        }
    }
})();