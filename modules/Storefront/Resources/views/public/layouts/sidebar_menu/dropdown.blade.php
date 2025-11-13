<ul class="list-inline" @click.stop>
    @foreach ($subMenus as $subMenu)
        <li
            class="{{ $subMenu->hasItems() ? 'dropdown sub-menu' : '' }}"
            @click="
                $($el).children('ul.list-inline').slideToggle(200);
                $($el).toggleClass('active');
            "
        >
            <a 
                href="{{ $subMenu->url() }}" 
                target="{{ $subMenu->target() }}" 
                @click.stop
                style="
                    @if($subMenu->isCategory() && $subMenu->background_color()) 
                        background-color: {{ $subMenu->background_color() }}; 
                    @elseif($subMenu->background_color()) 
                        background-color: {{ $subMenu->background_color() }}; 
                    @endif
                    @if($subMenu->isCategory() && $subMenu->text_color()) 
                        color: {{ $subMenu->text_color() }}; 
                    @elseif($subMenu->text_color()) 
                        color: {{ $subMenu->text_color() }}; 
                    @endif
                "
                data-hover-bg="{{ $subMenu->isCategory() && $subMenu->hover_background_color() ? $subMenu->hover_background_color() : ($subMenu->hover_background_color() ?? '') }}"
                data-hover-text="{{ $subMenu->isCategory() && $subMenu->hover_text_color() ? $subMenu->hover_text_color() : ($subMenu->hover_text_color() ?? '') }}"
                data-after-color="{{ $subMenu->after_color() ?? '' }}"
                onmouseover="this.style.setProperty('--hover-bg', '{{ $subMenu->isCategory() && $subMenu->hover_background_color() ? $subMenu->hover_background_color() : ($subMenu->hover_background_color() ?? '') }}'); this.style.setProperty('--hover-text', '{{ $subMenu->isCategory() && $subMenu->hover_text_color() ? $subMenu->hover_text_color() : ($subMenu->hover_text_color() ?? '') }}'); this.style.setProperty('--after-color', '{{ $subMenu->after_color() ?? '' }}');"
            >
                {{-- Kategori logo/ikon kontrolü --}}
                @if ($subMenu->isCategory())
                    @if ($subMenu->getCategory() && $subMenu->getCategory()->logo && $subMenu->getCategory()->logo->exists)
                        <img src="{{ $subMenu->getCategory()->logo->path }}" alt="{{ $subMenu->name() }}" class="menu-item-image" style="width: 24px; height: 24px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                    @else
                        <span class="menu-item-icon">
                            <i class="las la-folder"></i>
                        </span>
                    @endif
                @elseif ($subMenu->hasBackgroundImage())
                    <img src="{{ $subMenu->backgroundImageUrl() }}" alt="{{ $subMenu->name() }}" class="menu-item-image" style="width: 24px; height: 24px; margin-right: 8px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                @elseif ($subMenu->hasIcon())
                    <span class="menu-item-icon">
                        <i class="{{ $subMenu->icon() }}"></i>
                    </span>
                @endif

                {{ $subMenu->name() }}
            </a>

            @if ($subMenu->hasItems())
                <i class="las la-angle-right"></i>
            @endif

            @if ($subMenu->hasItems())
                @include('storefront::public.layouts.sidebar_menu.dropdown', ['subMenus' => $subMenu->items()])
            @endif
        </li>
    @endforeach
</ul>
