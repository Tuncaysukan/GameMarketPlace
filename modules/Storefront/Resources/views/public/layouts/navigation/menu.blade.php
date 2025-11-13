<li class="{{ mega_menu_classes($menu, $type) }}">
    <a
        href="{{ $menu->url() }}"
        class="nav-link menu-item"
        target="{{ $menu->target() }}"
        title="{{ $menu->name() }}"
        style="
            @if($menu->background_color()) background-color: {{ $menu->background_color() }}; @endif
            @if($menu->text_color()) color: {{ $menu->text_color() }}; @endif
        "
        data-hover-bg="{{ $menu->hover_background_color() ?? '' }}"
        data-hover-text="{{ $menu->hover_text_color() ?? '' }}"
        data-after-color="{{ $menu->after_color() ?? '' }}"
        onmouseover="this.style.setProperty('--hover-bg', '{{ $menu->hover_background_color() ?? '' }}'); this.style.setProperty('--hover-text', '{{ $menu->hover_text_color() ?? '' }}'); this.style.setProperty('--after-color', '{{ $menu->after_color() ?? '' }}');"
    >
        @if ($menu->hasBackgroundImage())
            <img src="{{ $menu->backgroundImageUrl() }}" alt="{{ $menu->name() }}" class="menu-item-image" style="width: 24px; height: 24px; margin-right: 8px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
        @endif

        @if ($menu->hasIcon())
            <span class="menu-item-icon">
                <i class="{{ $menu->icon() }}"></i>
            </span>
        @endif

        {{ $menu->name() }}

        @if ($menu->hasSubMenus())
            <i class="las la-angle-right"></i>
        @endif
    </a>

    @if ($menu->isAllCategoriesMenu())
        @include('storefront::public.layouts.navigation.all_categories', ['categories' => $menu->getAllCategories()])
    @elseif ($menu->isFluid())
        @include('storefront::public.layouts.navigation.fluid', ['subMenus' => $menu->subMenus()])
    @else
        @include('storefront::public.layouts.navigation.dropdown', ['subMenus' => $menu->subMenus()])
    @endif
</li>
