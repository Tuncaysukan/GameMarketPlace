<section x-data="Hero" class="home-section-wrap">
    @if($slider->desktopBackgroundFile && $slider->desktopBackgroundFile->path)
        <div class="background-image-container" 
            data-desktop-bg="{{ $slider->desktopBackgroundFile->path }}"
            @if($slider->mobileBackgroundFile && $slider->mobileBackgroundFile->path)
                data-mobile-bg="{{ $slider->mobileBackgroundFile->path }}"
            @endif
            style="background-image: url('{{ $slider->desktopBackgroundFile->path }}');">
        </div>
    @endif
    
    <div class="container">
        <div class="row">
            <div class="col-18">
                <div class="home-slider-wrap">
                    <div class="home-slider overflow-hidden swiper" data-speed="{{ $slider->speed }}"
                        data-autoplay="{{ $slider->autoplay ? 'true' : 'false' }}"
                        data-autoplay-speed="{{ $slider->autoplay_speed }}"
                        data-dots="{{ $slider->dots ? 'true' : 'false' }}"
                        data-arrows="{{ $slider->arrows ? 'true' : 'false' }}"
                        data-slides="{{ json_encode($slider->slides->map(function($slide) {
                            return [
                                'id' => $slide->id,
                                'logo' => $slide->logoFile && $slide->logoFile->path ? $slide->logoFile->path : null,
                                'color' => $slide->options['bullet_color'] ?? '#007bff'
                            ];
                        })) }}">
                        <div class="swiper-wrapper">
                            @foreach ($slider->slides as $slide)
                                <div class="swiper-slide">
                                    <a href="{{ $slide->call_to_action_url }}" class="swiper-slide">
                                        {{-- Desktop image (default) --}}
                                        <img src="{{ $slide->file->path }}" alt="" class="d-none d-md-block">

                                        {{-- Mobile image --}}
                                        @if ($slide->mobileFile && $slide->mobileFile->path)
                                            <img src="{{ $slide->mobileFile->path }}" alt=""
                                                class="d-block d-md-none">
                                        @else
                                            {{-- Fallback to desktop image on mobile --}}
                                            <img src="{{ $slide->file->path }}" alt=""
                                                class="d-block d-md-none">
                                        @endif


                                    </a>
                                </div>
                            @endforeach
                        </div>

                        @if ($slider->dots)
                            <div class="swiper-pagination"></div>
                        @endif

                        @if ($slider->arrows)
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($slider->desktopBackgroundFile && $slider->desktopBackgroundFile->path)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backgroundContainer = document.querySelector('.background-image-container');
    if (!backgroundContainer) return;
    
    const desktopBg = backgroundContainer.getAttribute('data-desktop-bg');
    const mobileBg = backgroundContainer.getAttribute('data-mobile-bg');
    
    function updateBackgroundImage() {
        if (window.innerWidth <= 767 && mobileBg) {
            backgroundContainer.style.backgroundImage = `url('${mobileBg}')`;
        } else if (desktopBg) {
            backgroundContainer.style.backgroundImage = `url('${desktopBg}')`;
        }
    }
    
    // İlk yükleme
    updateBackgroundImage();
    
    // Ekran boyutu değiştiğinde
    window.addEventListener('resize', updateBackgroundImage);
});
</script>
@endif
