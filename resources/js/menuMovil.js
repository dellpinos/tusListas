(function () {

    if (document.querySelector('#icono-menu-movil')) {

        document.addEventListener('DOMContentLoaded', () => {

            const btnMenu = document.querySelector('#icono-menu-movil');
            const sidebar = document.querySelector('.sidebar');
            const body = document.querySelector('body');

            btnMenu.addEventListener('click', () => {
                sidebar.classList.toggle('sidebar__movil-visible');
                body.classList.toggle('scroll-mobile'); // bloquea el scroll
            });


        });
    }


})();