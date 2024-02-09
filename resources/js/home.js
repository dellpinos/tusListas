(function () {

    document.addEventListener('DOMContentLoaded', () => {

        if (document.querySelector('.header-home')) {

            // Ocultar contacts en header
            const contact = document.querySelector('#header-home-contact');
            window.addEventListener('scroll', () => {

                if (window.scrollY < 500) {
                    contact.classList.remove('height-cero');

                } else {
                    contact.classList.add('height-cero');
                }
            });
        }

        if (document.querySelector('#tour-card')) {
            const playIcon = document.querySelector('#tour-play-icon');
            const tourCard = document.querySelector('#tour-card');

            playIcon.addEventListener('click', () => {

                tourCard.innerHTML = `<iframe width="800" height="450" class="tour__card" src="https://www.youtube.com/embed/HiVnGgYudLY" title="Cómo configurar VSCode para que sea ASOMBROSO! 😎🤓 | Extensiones, Tips y temas" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
            });
        }
    });
})();