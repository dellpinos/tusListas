import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css/bundle';
import 'swiper/css/navigation';




const opciones = {
    slidesPerView: 1,
    spaceBetween: 20,
    freeMode: true,
    loop: true,
    watchOverflow: true,
    speed: 500,

    keyboard: {
        enabled: true,
        onlyInViewport: false,
    },

    modules: [Navigation, Pagination],
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        hideOnClick: true,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        type: 'bullets',
        dynamicBullets: true,
    },
    breakpoints: {

        768: {
            slidesPerView: 2
        },
        1024: {
            slidesPerView: 3
        },
        1200: {
            slidesPerView: 3
        }
    }
}

const swiper = new Swiper('.swiper', opciones);

swiper.on('click', function () {
//    swiper.init();
});

export default swiper;


