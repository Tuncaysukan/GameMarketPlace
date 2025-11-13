import Swiper from "swiper";
import { Autoplay, Navigation, Pagination, Parallax } from "swiper/modules";

Alpine.data("Hero", () => ({
    init() {
        this.initHeroSlider();
    },

    initHeroSlider() {
        const { speed, autoplay, autoplaySpeed, dots, arrows, slides } =
            $(".home-slider").data();

        const slidesData = slides || [];

        if (dots && slidesData.length > 0) {
            slidesData.forEach((slide, index) => {
                const color = slide && slide.color ? slide.color : '#007bff';
                document.documentElement.style.setProperty(`--bullet-color-${index}`, color);
            });
        }

        const swiper = new Swiper(".home-slider", {
            modules: [Autoplay, Navigation, Pagination, Parallax],
            slidesPerView: 1,
            speed,
            parallax: true,
            ...(autoplay && {
                autoplay: {
                    delay: autoplaySpeed,
                    pauseOnMouseEnter: true,
                },
            }),
            ...(arrows && {
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            }),
            ...(dots && {
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    renderBullet: function (index, className) {
                        const slide = slidesData[index];
                        const logoImg = slide && slide.logo
                            ? `<img src="${slide.logo}" alt="Logo">`
                            : '';

                        return `<span class="${className}" data-bullet-index="${index}">${logoImg}</span>`;
                    },
                },
            }),
        });

        // Mobil cihazlarda pagination'ı kaydırılabilir yap
        if (dots) {
            // Pagination elementinin yüklenmesini bekle
            setTimeout(() => {
                this.setupMobilePagination(slidesData.length);
            }, 200);
        }
    },

    setupMobilePagination(slideCount) {
        // Mobil cihaz kontrolü
        const isMobile = window.innerWidth <= 767;

        // Mobilde 3 satır, her satırda yaklaşık 3-4 bullet sığabiliyor
        // Daha konservatif hesaplama yapalım
        const screenWidth = window.innerWidth;
        const bulletsPerRow = Math.floor(screenWidth / 100); // 100px bullet + margin genişliği
        const maxVisibleBullets = bulletsPerRow * 3; // 3 satır

        // 3'den fazla bullet varsa carousel yap (test için)
        if (isMobile && slideCount > 3) {
            const paginationEl = document.querySelector('.swiper-pagination');
            if (paginationEl) {
                paginationEl.classList.add('swiper-pagination-scrollable');

                // Touch ve mouse scroll desteği
                let isDown = false;
                let startX;
                let scrollLeft;

                paginationEl.addEventListener('mousedown', (e) => {
                    isDown = true;
                    startX = e.pageX - paginationEl.offsetLeft;
                    scrollLeft = paginationEl.scrollLeft;
                    paginationEl.style.cursor = 'grabbing';
                });

                paginationEl.addEventListener('mouseleave', () => {
                    isDown = false;
                    paginationEl.style.cursor = 'grab';
                });

                paginationEl.addEventListener('mouseup', () => {
                    isDown = false;
                    paginationEl.style.cursor = 'grab';
                });

                paginationEl.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - paginationEl.offsetLeft;
                    const walk = (x - startX) * 2;
                    paginationEl.scrollLeft = scrollLeft - walk;
                });

                // Touch events için
                let touchStartX = 0;
                let touchScrollLeft = 0;

                paginationEl.addEventListener('touchstart', (e) => {
                    touchStartX = e.touches[0].clientX;
                    touchScrollLeft = paginationEl.scrollLeft;
                });

                paginationEl.addEventListener('touchmove', (e) => {
                    const touchX = e.touches[0].clientX;
                    const walk = (touchStartX - touchX) * 1.5;
                    paginationEl.scrollLeft = touchScrollLeft + walk;
                });
            }
        }

        // Ekran boyutu değiştiğinde yeniden kontrol et
        window.addEventListener('resize', () => {
            const paginationEl = document.querySelector('.swiper-pagination');
            if (paginationEl) {
                const isMobileNow = window.innerWidth <= 767;

                if (isMobileNow && slideCount > 3) {
                    paginationEl.classList.add('swiper-pagination-scrollable');
                } else {
                    paginationEl.classList.remove('swiper-pagination-scrollable');
                }
            }
        });
    },
}));
