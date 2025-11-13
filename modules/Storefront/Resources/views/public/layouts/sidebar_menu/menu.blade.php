<div class="sidebar-menu-container" x-data="{ 
    currentLevel: 'main',
    currentMenu: null,
    menuHistory: [],
    goToSubMenu(menuData) {
        this.menuHistory.push({ level: this.currentLevel, menu: this.currentMenu });
        this.currentLevel = 'submenu';
        this.currentMenu = menuData;
    },
    goBack() {
        if (this.menuHistory.length > 0) {
            const previous = this.menuHistory.pop();
            this.currentLevel = previous.level;
            this.currentMenu = previous.menu;
        } else {
            this.currentLevel = 'main';
            this.currentMenu = null;
        }
    }
}">
    
    <!-- Ana Menü -->
    <div x-show="currentLevel === 'main'" 
         :style="currentLevel !== 'main' ? 'height: 0; overflow: hidden;' : ''">
        <ul class="list-inline sidebar-menu">
            @foreach ($menu->menus() as $menuItem)
                <li class="{{ $menuItem->hasSubMenus() ? 'has-submenu' : '' }}{{ $menuItem->name() === trans('storefront::layouts.all_categories') || $menuItem->url() === route('categories.index') ? ' all-categories-menu' : '' }}">
                    <a
                        href="{{ $menuItem->url() }}"
                        class="menu-item"
                        target="{{ $menuItem->target() }}"
                        @click.stop
                        style="
                            @if($menuItem->background_color()) background-color: {{ $menuItem->background_color() }}; @endif
                            @if($menuItem->text_color()) color: {{ $menuItem->text_color() }}; @endif
                        "
                        data-hover-bg="{{ $menuItem->hover_background_color() ?? '' }}"
                        data-hover-text="{{ $menuItem->hover_text_color() ?? '' }}"
                        data-after-color="{{ $menuItem->after_color() ?? '' }}"
                        onmouseover="this.style.setProperty('--hover-bg', '{{ $menuItem->hover_background_color() ?? '' }}'); this.style.setProperty('--hover-text', '{{ $menuItem->hover_text_color() ?? '' }}'); this.style.setProperty('--after-color', '{{ $menuItem->after_color() ?? '' }}');"
                    >
                        @if ($menuItem->hasBackgroundImage())
                            <img src="{{ $menuItem->backgroundImageUrl() }}" alt="{{ $menuItem->name() }}" class="menu-item-image" style="width: 24px; height: 24px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                        @endif

                        @if ($menuItem->hasIcon())
                            <span class="menu-item-icon">
                                <i class="{{ $menuItem->icon() }}"></i>
                            </span>
                        @endif

                        {{ $menuItem->name() }}
                    </a>

                    @if ($menuItem->hasSubMenus())
                        <button 
                            class="submenu-toggle"
                            @click="goToSubMenu({
                                name: '{{ $menuItem->name() }}',
                                subMenus: @js($menuItem->subMenus()->map(function($subMenu, $index) {
                                    return [
                                        'id' => $subMenu->id ?? 'submenu_' . $index,
                                        'name' => $subMenu->name(),
                                        'url' => $subMenu->url(),
                                        'target' => $subMenu->target(),
                                        'background_color' => $subMenu->background_color(),
                                        'text_color' => $subMenu->text_color(),
                                        'hover_background_color' => $subMenu->hover_background_color(),
                                        'hover_text_color' => $subMenu->hover_text_color(),
                                        'has_items' => $subMenu->hasItems(),
                                        'is_category' => $subMenu->isCategory(),
                                        'icon' => $subMenu->icon(),
                                        'background_image' => $subMenu->hasBackgroundImage() ? ['path' => $subMenu->backgroundImageUrl()] : null,
                                        'logo' => $subMenu->isCategory() && $subMenu->getCategory() && $subMenu->getCategory()->logo && $subMenu->getCategory()->logo->exists ? ['path' => $subMenu->getCategory()->logo->path, 'exists' => true] : null,
                                        'subMenus' => $subMenu->hasItems() ? $subMenu->items()->map(function($subSubMenu, $subIndex) {
                                            return [
                                                'id' => $subSubMenu->id ?? 'subsubmenu_' . $subIndex,
                                                'name' => $subSubMenu->name(),
                                                'url' => $subSubMenu->url(),
                                                'target' => $subSubMenu->target(),
                                                'background_color' => $subSubMenu->background_color(),
                                                'text_color' => $subSubMenu->text_color(),
                                                'hover_background_color' => $subSubMenu->hover_background_color(),
                                                'hover_text_color' => $subSubMenu->hover_text_color(),
                                                'has_items' => $subSubMenu->hasItems(),
                                                'is_category' => $subSubMenu->isCategory(),
                                                'icon' => $subSubMenu->icon(),
                                                'background_image' => $subSubMenu->hasBackgroundImage() ? ['path' => $subSubMenu->backgroundImageUrl()] : null,
                                                'logo' => $subSubMenu->isCategory() && $subSubMenu->getCategory() && $subSubMenu->getCategory()->logo && $subSubMenu->getCategory()->logo->exists ? ['path' => $subSubMenu->getCategory()->logo->path, 'exists' => true] : null
                                            ];
                                        })->toArray() : []
                                    ];
                                })->toArray())
                            })"
                            title="{{ trans('storefront::layouts.view_submenu') }}"
                        >
                            <i class="las la-angle-right"></i>
                        </button>
                    @endif
                </li>
            @endforeach

            @if ($type === 'category_menu')
                <li class="more-categories">
                    <a href="{{ route('categories.index') }}" class="menu-item">
                        <span class="menu-item-icon">
                            <i class="las la-plus-square"></i>
                        </span>

                        {{ trans('storefront::layouts.all_categories') }}
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Alt Menü -->
    <div x-show="currentLevel === 'submenu'" 
         
         :style="currentLevel !== 'submenu' ? 'height: 0; overflow: hidden;' : ''">
        <div class="submenu-header">
            <button class="back-button" @click="goBack()">
                <i class="las la-arrow-left"></i>
            </button>
            <h4 class="submenu-title" x-text="currentMenu ? currentMenu.name : ''"></h4>
        </div>

        <ul class="list-inline sidebar-submenu" x-show="currentMenu && currentMenu.subMenus">
            <template x-for="(subMenu, index) in (currentMenu ? currentMenu.subMenus : [])" :key="subMenu.id || index">
                <li class="submenu-item">
                    <a 
                        :href="subMenu.url" 
                        :target="subMenu.target || '_self'" 
                        class="menu-item"
                        :style="`
                            ${subMenu.background_color ? 'background-color: ' + subMenu.background_color + ';' : ''}
                            ${subMenu.text_color ? 'color: ' + subMenu.text_color + ';' : ''}
                        `"
                        :data-hover-bg="subMenu.hover_background_color || ''"
                        :data-hover-text="subMenu.hover_text_color || ''"
                        @mouseover="
                            this.style.setProperty('--hover-bg', subMenu.hover_background_color || '');
                            this.style.setProperty('--hover-text', subMenu.hover_text_color || '');
                        "
                    >
                        <!-- Kategori logo/ikon kontrolü -->
                        <template x-if="subMenu.is_category">
                            <template x-if="subMenu.logo && subMenu.logo.exists">
                                <img :src="subMenu.logo.path" :alt="subMenu.name" class="menu-item-image" style="width: 24px; height: 24px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                            </template>
                            <template x-if="!subMenu.logo || !subMenu.logo.exists">
                                <span class="menu-item-icon">
                                    <i class="las la-folder"></i>
                                </span>
                            </template>
                        </template>
                        
                        <template x-if="!subMenu.is_category && subMenu.background_image">
                            <img :src="subMenu.background_image.path" :alt="subMenu.name" class="menu-item-image" style="width: 24px; height: 24px; vertical-align: middle; object-fit: cover; border-radius: 2px;">
                        </template>
                        
                        <template x-if="!subMenu.is_category && subMenu.icon">
                            <span class="menu-item-icon">
                                <i :class="subMenu.icon"></i>
                            </span>
                        </template>

                        <span x-text="subMenu.name"></span>
                    </a>

                    <template x-if="subMenu.has_items">
                        <button 
                            class="submenu-toggle"
                            @click="goToSubMenu({
                                name: subMenu.name,
                                subMenus: subMenu.subMenus || []
                            })"
                            title="{{ trans('storefront::layouts.view_submenu') }}"
                        >
                            <i class="las la-angle-right"></i>
                        </button>
                    </template>
                </li>
            </template>
        </ul>
    </div>
</div>
