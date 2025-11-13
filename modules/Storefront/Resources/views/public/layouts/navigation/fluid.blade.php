<ul
    class="list-inline fluid-menu-wrap custom-scrollbar"

    @if ($menu->hasBackgroundImage())
        style="background-image: url({{ $menu->backgroundImage() }});"
    @endif
>
    <li>
        <div class="fluid-menu-content">
            @foreach ($subMenus as $subMenu)
                <div class="fluid-menu-list">
                    <h5 class="fluid-menu-title">
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
                                <img src="{{ $subMenu->backgroundImageUrl() }}" alt="{{ $subMenu->name() }}" class="menu-item-image" style="width: 24px; height: 24px; margin-right: 8px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                            @endif

                            {{ $subMenu->name() }}
                        </a>
                    </h5>

                    <ul class="list-inline fluid-sub-menu-list">
                        @foreach ($subMenu->items() as $item)
                            <li>
                                <a 
                                    href="{{ $item->url() }}" 
                                    target="{{ $subMenu->target() }}" 
                                    title="{{ $item->name() }}"
                                    style="
                                        @if($item->background_color()) background-color: {{ $item->background_color() }}; @endif
                                        @if($item->text_color()) color: {{ $item->text_color() }}; @endif
                                    "
                                    data-hover-bg="{{ $item->hover_background_color() ?? '' }}"
                                    data-hover-text="{{ $item->hover_text_color() ?? '' }}"
                                    data-after-color="{{ $item->after_color() ?? '' }}"
                                    onmouseover="this.style.setProperty('--hover-bg', '{{ $item->hover_background_color() ?? '' }}'); this.style.setProperty('--hover-text', '{{ $item->hover_text_color() ?? '' }}'); this.style.setProperty('--after-color', '{{ $item->after_color() ?? '' }}');"
                                >
                                    @if ($item->hasBackgroundImage())
                                        <img src="{{ $item->backgroundImageUrl() }}" alt="{{ $item->name() }}" class="menu-item-image" style="width: 24px; height: 24px; margin-right: 8px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                                    @endif

                                    {{ $item->name() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </li>
</ul>
