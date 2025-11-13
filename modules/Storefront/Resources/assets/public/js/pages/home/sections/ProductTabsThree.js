import ProductTabsMixin from "../../../mixins/ProductTabsMixin";
import "../../../components/ProductCard";

Alpine.data("ProductTabsThree", (tabs) => ({
    ...ProductTabsMixin(tabs),

    init() {
        this.changeTab(0);
    },

    url(tabIndex) {
        return route("storefront.tab_products.index", {
            sectionNumber: 3,
            tabNumber: tabIndex + 1,
        });
    },

    selector() {
        return ".products-grid";
    },
}));