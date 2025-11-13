<section class="top-nav-wrap">
    <div class="container">
        <div class="top-nav">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Sol taraf - Menüler -->
                <div class="top-nav-left">
                    <ul class="list-inline top-nav-right-list"> 
                        @if($topNavMenu)
                            @foreach ($topNavMenu->menus() as $menu)
                                <li class="{{ $menu->hasSubMenus() ? 'dropdown custom-dropdown' : '' }}">
                                    @if($menu->hasSubMenus())
                                        <div
                                            x-data="{
                                                open: false
                                            }"
                                            :class="{ active: open }"
                                            @click.away="open = false"
                                        >
                                            <a href="{{ $menu->url() }}" class="nav-link dropdown-toggle" @click="open = !open">
                                                <div class="nav-content">
                                                    @if($menu->hasIcon())
                                                        <i class="{{ $menu->icon() }}"></i>
                                                    @endif
                                                    <span class="nav-text">{{ $menu->name() }}</span>
                                                </div>
                                                <i class="las la-angle-down"></i>
                                            </a>
                                            <ul
                                                x-cloak
                                                x-show="open"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 transform scale-100"
                                                x-transition:leave-end="opacity-0 transform scale-95"
                                                class="dropdown-menu"
                                                :class="{ active: open }"
                                            >
                                                @foreach($menu->subMenus() as $subMenu)
                                                    <li class="dropdown-item">
                                                        <a href="{{ $subMenu->url() }}" target="{{ $subMenu->target() }}">
                                                            @if($subMenu->hasIcon())
                                                                <i class="{{ $subMenu->icon() }}"></i>
                                                            @else
                                                                <i class="las la-circle"></i>
                                                            @endif
                                                            <span>{{ $subMenu->name() }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ $menu->url() }}" class="nav-link" target="{{ $menu->target() }}">
                                            <div class="nav-content">
                                                @if($menu->hasIcon())
                                                    <i class="{{ $menu->icon() }}"></i>
                                                @endif
                                                <span class="nav-text">{{ $menu->name() }}</span>
                                            </div>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <!-- Varsayılan menüler (top_nav_menu ayarlanmamışsa) -->
                            <li>
                                <a href="#" class="nav-link">
                                    <span class="nav-text">% Günün Fırsatları</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link">
                                    <span class="nav-text">Blog</span>
                                </a>
                            </li>
                            <li>
                                <div
                                    x-data="{
                                        open: false
                                    }"
                                    class="dropdown custom-dropdown"
                                    :class="{ active: open }"
                                    @click.away="open = false"
                                >
                                    <a href="#" class="nav-link dropdown-toggle" @click="open = !open">
                                        <span class="nav-text">Destek Merkezi</span>
                                        <i class="las la-angle-down"></i>
                                    </a>
                                    <ul
                                        x-cloak
                                        x-show="open"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="dropdown-menu"
                                        :class="{ active: open }"
                                    >
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-question-circle"></i>
                                                <span>Yardım</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-envelope"></i>
                                                <span>İletişim</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-headset"></i>
                                                <span>Canlı Destek</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div
                                    x-data="{
                                        open: false
                                    }"
                                    class="dropdown custom-dropdown"
                                    :class="{ active: open }"
                                    @click.away="open = false"
                                >
                                    <a href="#" class="nav-link dropdown-toggle" @click="open = !open">
                                        <span class="nav-text">Kurumsal</span>
                                        <i class="las la-angle-down"></i>
                                    </a>
                                    <ul
                                        x-cloak
                                        x-show="open"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="dropdown-menu"
                                        :class="{ active: open }"
                                    >
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-file-alt"></i>
                                                <span>Hakkımızda</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-id-card"></i>
                                                <span>Kurumsal Kimlik</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-user-check"></i>
                                                <span>Güvenilirlik</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-at"></i>
                                                <span>İletişim</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-heart-broken"></i>
                                                <span>Şikayetim Var</span>
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="#">
                                                <i class="las la-leaf"></i>
                                                <span>Müşteri Yorumları</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Orta - Welcome Text -->
                <div class="top-nav-center">
                    <span class="welcome-text">{{ setting('storefront_welcome_text', 'Ücretsiz Çekilişlere Hemen Katılın, Bedava E-Pin Kazanın!') }}</span>
                </div>

                <!-- Sağ taraf - Sosyal Medya İkonları -->
                <div class="top-nav-right">
                    <ul class="list-inline social-links">
                        @if (social_links()->isNotEmpty())
                            @foreach (social_links() as $icon => $socialLink)
                                <li>
                                    <a href="{{ $socialLink }}" title="{{ social_link_name($icon) }}"
                                        target="_blank" class="social-link-item {{ str_replace(' ', '-', $icon) }}">
                                        @if ($icon === 'lab la-twitter')
                                            <svg class="twitter-icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 30 30" width="20px" height="20px">
                                                <path
                                                    d="M26.37,26l-8.795-12.822l0.015,0.012L25.52,4h-2.65l-6.46,7.48L11.28,4H4.33l8.211,11.971L12.54,15.97L3.88,26h2.65 l7.182-8.322L19.42,26H26.37z M10.23,6l12.34,18h-2.1L8.12,6H10.23z" />
                                            </svg>
                                        @else
                                            <i class="{{ $icon }}"></i>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
