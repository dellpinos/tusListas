import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css/bundle';
import 'swiper/css/navigation';

(function () {

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.swiper')) {


            const opciones = {
                slidesPerView: 2,
                spaceBetween: 15,
                freeMode: true,
                loop: true,
                
                modules: [ Navigation, Pagination ],
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    type: 'bullets',
                    dynamicBullets: true,


                }
                // breakpoints: {
                //     768: {
                //         slidesPerView: 2
                //     },
                //     1024: {
                //         slidesPerView: 3
                //     },
                //     1200: {
                //         slidesPerView: 4
                //     }
                // }
            }

            const swiper = new Swiper('.swiper', opciones);


        }

    });


})();


