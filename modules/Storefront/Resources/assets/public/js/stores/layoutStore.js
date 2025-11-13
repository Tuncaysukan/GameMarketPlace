Alpine.store("layout", {
    sidebarMenuOpen: false,
    sidebarCartOpen: false,
    sidebarFilterOpen: false,
    localizationMenuOpen: false,
    overlay: false,
    darkMode: false,

    get isOpenSidebarMenu() {
        return this.sidebarMenuOpen;
    },

    get isOpenSidebarCart() {
        return this.sidebarCartOpen;
    },

    get isOpenSidebarFilter() {
        return this.sidebarFilterOpen;
    },

    get isOpenlocalizationMenu() {
        return this.localizationMenuOpen;
    },

    openSidebarMenu() {
        this.sidebarMenuOpen = true;

        this.showOverlay();
    },

    closeSidebarMenu() {
        this.sidebarMenuOpen = false;

        this.hideOverlay();
    },

    openSidebarCart(event) {
        if (event) {
            event.preventDefault();
        }

        if (route().current("checkout.create")) {
            window.location.href = route("cart.index");

            return;
        }

        this.sidebarCartOpen = true;

        this.showOverlay();
    },

    closeSidebarCart() {
        this.sidebarCartOpen = false;

        this.hideOverlay();
    },

    openSidebarFilter() {
        this.sidebarFilterOpen = true;

        this.showOverlay();
    },

    closeSidebarFilter() {
        this.sidebarFilterOpen = false;

        this.hideOverlay();
    },

    openLocalizationMenu() {
        this.localizationMenuOpen = true;

        this.showOverlay();
    },

    closeLocalizationMenu() {
        this.localizationMenuOpen = false;

        this.hideOverlay();
    },

    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        
        if (this.darkMode) {
            document.documentElement.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.documentElement.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
        
        // Update menu colors when dark mode is toggled
        this.updateMenuColors();
    },

    updateMenuColors() {
        // Dispatch a custom event to trigger menu color update
        window.dispatchEvent(new CustomEvent('darkModeToggled'));
    },

    showOverlay() {
        this.overlay = true;
    },

    hideOverlay() {
        this.overlay = false;
    },
});
