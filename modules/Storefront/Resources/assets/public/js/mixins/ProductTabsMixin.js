export default function (tabs) {
    return {
        tabs,
        activeTab: null,
        loading: false,
        products: [],

        get hasAnyProduct() {
            return this.products.length;
        },

        tab(index) {
            return this.tabs[index].name || this.tabs[index];
        },

        async changeTab(index) {
            if (
                this.activeTab === this.tab(index) ||
                this.tab(index) === undefined
            ) {
                return;
            }

            this.activeTab = this.tab(index);

            this.fetchProducts(index);
        },

        classes(index) {
            return {
                active: this.activeTab === this.tab(index) && !this.loading,
                loading: this.activeTab === this.tab(index) && this.loading,
            };
        },

        hideSkeletons() {
            const skeletons = document.querySelectorAll(
                `${this.selector()} .product-skeleton`
            );

            skeletons.forEach((skeleton) => skeleton.remove());
        },

        async fetchProducts(tabIndex = 0) {
            this.loading = true;

            try {
                const response = await axios.get(this.url(tabIndex));

                this.products = response.data;
            } catch (error) {
                // handle error
            } finally {
                this.loading = false;

                this.hideSkeletons();
            }
        },
    };
}
