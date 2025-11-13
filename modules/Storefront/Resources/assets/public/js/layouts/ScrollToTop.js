Alpine.data("ScrollToTop", () => ({
    percent: 0,
    scrolled: false,
    isScrolling: false,

    get circumference() {
        return 19 * 2 * Math.PI;
    },

    init() {
        this.addEventListener();
    },

    scrollToTop() {
        if (this.isScrolling) return;
        
        this.isScrolling = true;
        window.scrollTo({ top: 0, behavior: "instant" });
        
        setTimeout(() => {
            this.isScrolling = false;
        }, 100);
    },

    addEventListener() {
        let ticking = false;

        window.addEventListener("scroll", () => {
            if (this.isScrolling || ticking) return;
            
            requestAnimationFrame(() => {
                let windowScroll =
                    document.body.scrollTop || document.documentElement.scrollTop;
                let height =
                    document.documentElement.scrollHeight -
                    document.documentElement.clientHeight;

                this.percent = Math.round((windowScroll / height) * 100);
                this.scrolled = window.scrollY > 100;
                ticking = false;
            });
            ticking = true;
        });
    },
}));
