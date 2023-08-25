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
            campoPersonalizado.classList.remove('formulario__campo--no-activo');

        } else {
            campoPersonalizado.disabled = true;
            campoPersonalizado.classList.add('formulario__campo--no-activo');
            campoPersonalizado.value = '';
        }
    }
})();