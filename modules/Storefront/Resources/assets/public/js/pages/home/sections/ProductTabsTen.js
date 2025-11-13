import ProductTabsMixin from "../../../mixins/ProductTabsMixin";
import "../../../components/ProductCard";

Alpine.data("ProductTabsTen", (tabs) => ({
    ...ProductTabsMixin(tabs),

    init() {
        this.changeTab(0);
    },

    url(tabIndex) {
        return route("storefront.tab_products.index", {
            sectionNumber: 10,
            tabNumber: tabIndex + 1,
        });
    },

    selector() {
        return ".products-grid";
    },

}));