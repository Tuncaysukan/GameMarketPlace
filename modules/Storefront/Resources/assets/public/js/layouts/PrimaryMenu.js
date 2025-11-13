import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";

Alpine.data("PrimaryMenu", () => ({
    init() {
        new Swiper(".primary-menu", {
            modules: [Navigation, Pagination],
            slidesPerView: "auto",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        this.adjustMegaMenuWidth();
        window.addEventListener('resize', () => {
            this.adjustMegaMenuWidth();
        });
    },

    adjustMegaMenuWidth() {
        const navigationInner = document.querySelector('.navigation-inner');
        const megaMenus = document.querySelectorAll('.all-categories-wrap');

        if (navigationInner && megaMenus.length > 0) {
            const navigationRect = navigationInner.getBoundingClientRect();

            const navigationWidth = navigationRect.width;

            megaMenus.forEach(menu => {
                menu.style.width = `${navigationWidth}px`;
                menu.style.maxWidth = `${navigationWidth}px`;
            });
        }
    }
}));
