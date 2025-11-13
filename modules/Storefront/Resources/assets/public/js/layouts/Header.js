import "./header/HeaderSearch";

Alpine.data("Header", () => ({
    stickyHeader: false,
    showStickyHeader: false,
    originalHeaderHeight: null,
    lastStickyState: null,
    mobileSearchOpen: false,

    get isStickyHeader() {
        return this.stickyHeader;
    },

    get isShowingStickyHeader() {
        return this.showStickyHeader;
    },

    init() {
        this.$nextTick(() => {
            this.originalHeaderHeight = this.$refs.header.offsetHeight;
            this.addEventListeners();
            this.checkStickyState();
        });
        this.initDarkMode();
    },

    initDarkMode() {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        if (darkMode) {
            document.documentElement.classList.add('dark-mode');
            this.$store.layout.darkMode = true;
        }
        this.updateMenuColors();
    },

    updateMenuColors() {
        const isDarkMode = document.documentElement.classList.contains('dark-mode');
        const menuItems = document.querySelectorAll('.nav-link.menu-item[style], .sidebar-menu .menu-item[style]');
        
        menuItems.forEach(item => {
            if (isDarkMode) {
                // Store original colors for light mode restoration
                if (!item.dataset.originalBgColor && item.style.backgroundColor) {
                    item.dataset.originalBgColor = item.style.backgroundColor;
                }
                if (!item.dataset.originalTextColor && item.style.color) {
                    item.dataset.originalTextColor = item.style.color;
                }
                
                // Apply dark mode colors
                if (item.style.backgroundColor) {
                    item.style.backgroundColor = 'var(--card-bg)';
                }
                if (item.style.color) {
                    item.style.color = 'var(--text-primary)';
                }
                
                // Update hover colors in data attributes
                item.dataset.darkModeActive = 'true';
                
            } else {
                // Restore original colors for light mode
                if (item.dataset.originalBgColor) {
                    item.style.backgroundColor = item.dataset.originalBgColor;
                }
                if (item.dataset.originalTextColor) {
                    item.style.color = item.dataset.originalTextColor;
                }
                
                item.dataset.darkModeActive = 'false';
            }
            
            // Update mouseover behavior
            this.updateMenuItemHover(item, isDarkMode);
        });
    },

    updateMenuItemHover(item, isDarkMode) {
        // Remove existing hover handlers
        item.onmouseover = null;
        item.onmouseout = null;
        
        if (isDarkMode) {
            item.onmouseover = () => {
                item.style.color = 'var(--color-primary)';
                item.style.backgroundColor = 'var(--bg-secondary)';
                // Preserve after-color for border
                const afterColor = item.dataset.afterColor;
                if (afterColor) {
                    item.style.setProperty('--after-color', afterColor);
                }
            };
            
            item.onmouseout = () => {
                item.style.color = 'var(--text-primary)';
                item.style.backgroundColor = 'var(--card-bg)';
            };
        } else {
            // Restore original hover behavior
            const originalHoverBg = item.dataset.hoverBg;
            const originalHoverText = item.dataset.hoverText;
            const originalAfterColor = item.dataset.afterColor;
            const originalBgColor = item.dataset.originalBgColor || 'transparent';
            const originalTextColor = item.dataset.originalTextColor || '';
            
            item.onmouseover = () => {
                if (originalHoverBg) item.style.backgroundColor = originalHoverBg;
                if (originalHoverText) item.style.color = originalHoverText;
                if (originalAfterColor) {
                    item.style.setProperty('--after-color', originalAfterColor);
                }
            };
            
            item.onmouseout = () => {
                item.style.backgroundColor = originalBgColor;
                item.style.color = originalTextColor;
            };
        }
    },

    checkStickyState() {
        if (window.innerWidth <= 768) return;
        
        const scrollThreshold = 100;
        const shouldBeSticky = window.scrollY > scrollThreshold;
        
        if (this.lastStickyState !== shouldBeSticky) {
            this.lastStickyState = shouldBeSticky;
            
            if (shouldBeSticky) {
                this.stickyHeader = true;
                this.$refs.header.style.paddingTop = `${this.originalHeaderHeight}px`;
                
                setTimeout(() => {
                    this.showStickyHeader = true;
                }, 50);
            } else {
                this.stickyHeader = false;
                this.showStickyHeader = false;
                this.$refs.header.style.paddingTop = "0px";
            }
        }
    },

    addEventListeners() {
        let ticking = false;

        window.addEventListener("resize", () => {
            this.checkStickyState();
        });

        window.addEventListener("scroll", () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.checkStickyState();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Listen for dark mode toggle events
        window.addEventListener("darkModeToggled", () => {
            this.updateMenuColors();
        });
    },
}));
