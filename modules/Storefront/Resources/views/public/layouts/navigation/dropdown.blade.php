@if ($subMenus->isNotEmpty())
    <ul class="list-inline sub-menu">
        @foreach ($subMenus as $subMenu)
            <li class="{{ $subMenu->hasItems() ? 'dropdown' : '' }}">
                <a 
                    href="{{ $subMenu->url() }}" 
                    target="{{ $subMenu->target() }}" 
                    title="{{ $subMenu->name() }}"
                    style="
                        @if($subMenu->background_color()) background-color: {{ $subMenu->background_color() }}; @endif
                        @if($subMenu->text_color()) color: {{ $subMenu->text_color() }}; @endif
                    "
                    data-hover-bg="{{ $subMenu->hover_background_color() ?? '' }}"
                    data-hover-text="{{ $subMenu->hover_text_color() ?? '' }}"
                    data-after-color="{{ $subMenu->after_color() ?? '' }}"
                    onmouseover="this.style.setProperty('--hover-bg', '{{ $subMenu->hover_background_color() ?? '' }}'); this.style.setProperty('--hover-text', '{{ $subMenu->hover_text_color() ?? '' }}'); this.style.setProperty('--after-color', '{{ $subMenu->after_color() ?? '' }}');"
                >
                    @if ($subMenu->hasBackgroundImage())
                        <img src="{{ $subMenu->backgroundImageUrl() }}" alt="{{ $subMenu->name() }}" class="menu-item-image" style="width: 24px; height: 24px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                    @endif

                    {{ $subMenu->name() }}
                </a>

                @if ($subMenu->hasItems())
                    @include('storefront::public.layouts.navigation.dropdown', ['subMenus' => $subMenu->items()])
                @endif
            </li>
        @endforeach
    </ul>
@endif
