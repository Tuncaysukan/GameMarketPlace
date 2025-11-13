import Swiper from "swiper";

Alpine.data("FooterPaymentMethods", () => ({
    init() {
        this.initPaymentMethodsSlider();
    },

    initPaymentMethodsSlider() {
        const swiperElement = document.querySelector('.payment-methods-swiper');

        if (swiperElement) {
            new Swiper(".payment-methods-swiper", {
                slidesPerView: 'auto',
                spaceBetween: 12,
                allowTouchMove: true,
                grabCursor: true,
            });
        }
    },
}));
