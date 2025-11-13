import ProductTabsMixin from "../../../mixins/ProductTabsMixin";
import "../../../components/ProductCard";

Alpine.data("GridProducts", (tabs) => ({
    ...ProductTabsMixin(tabs),

    init() {
        this.changeTab(0);
    },

    url(tabIndex) {
        return route("storefront.product_grid.index", {
            tabNumber: tabIndex + 1,
        });
    },

    selector() {
        return ".products-grid";
    },
}));
