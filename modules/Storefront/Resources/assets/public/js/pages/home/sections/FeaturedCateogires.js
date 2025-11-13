import Swiper from "swiper";
import { Autoplay } from "swiper/modules";
import ProductTabsMixin from "../../../mixins/ProductTabsMixin";
import "../../../components/ProductCard";

Alpine.data("FeaturedCategories", (tabs) => ({
    ...ProductTabsMixin(tabs),

    init() {
        this.initCategoriesSlider();
        this.changeTab(0);
    },

    initCategoriesSlider() {
        // Mobilde autoplay'i devre dışı bırak
        const isMobile = window.innerWidth < 768;

        new Swiper(".featured-categories-slider", {
            modules: [Autoplay],
            slidesPerView: 'auto',
            spaceBetween: 12,
            freeMode: false,
            loop: false,
            autoplay: isMobile ? false : {
                delay: 2500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
                reverseDirection: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 8,
                    slidesOffsetAfter: 200,
                    loop: false,
                },
                480: {
                    slidesPerView: 3.2,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 'auto',
                    spaceBetween: 12,
                },
            },
        });
    },

    url(tabIndex) {
        return route("storefront.featured_category_products.index", {
            categoryNumber: tabIndex + 1,
        });
    },

    selector() {
        return ".products-grid";
    },
}));
