<header x-ref="header" x-data="Header" class="header-wrap">
    <div class="header-placeholder"></div>
    <div
        class="header-wrap-inner"
        :class="{
            'sticky': isStickyHeader,
            'show': isShowingStickyHeader
        }"
    >
        <div class="container">
            <div class="header-desktop-row d-flex flex-nowrap justify-content-between align-items-center position-relative">
                <div class="header-column-left d-flex align-items-center">
                    <div class="sidebar-menu-icon-wrap" @click="$store.layout.openSidebarMenu()">
                        <div class="sidebar-menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="150px" height="150px">
                                <path d="M 3 9 A 1.0001 1.0001 0 1 0 3 11 L 47 11 A 1.0001 1.0001 0 1 0 47 9 L 3 9 z M 3 24 A 1.0001 1.0001 0 1 0 3 26 L 47 26 A 1.0001 1.0001 0 1 0 47 24 L 3 24 z M 3 39 A 1.0001 1.0001 0 1 0 3 41 L 47 41 A 1.0001 1.0001 0 1 0 47 39 L 3 39 z"></path>
                            </svg>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" class="header-logo">
                        @if (is_null($logo))
                            <h3>{{ setting('store_name') }}</h3>
                        @else
                            <img src="{{ $logo }}" alt="{{ setting('store_name') ?? 'Logo' }}">
                        @endif
                    </a>
                </div>

                <div class="header-column-center">
                    @include('storefront::public.layouts.header.header_search')
                </div>

                <div class="header-column-right d-flex align-items-center">
                    <div class="header-column-right-item header-dark-mode">
                        <div class="icon-wrap" @click="$store.layout.toggleDarkMode()">
                            <i class="las la-moon"></i>
                        </div>
                    </div>

                    <div class="header-search-toggle mobile-only" x-data="{ searchOpen: false }">
                        <button class="search-toggle-btn" @click="searchOpen = !searchOpen">
                            <i class="las la-search"></i>
                        </button>
                    </div>

                    @auth
                    <a
                        href="{{ route('cart.index') }}"
                        class="header-column-right-item header-cart"
                        @click="$store.layout.openSidebarCart($event)"
                    >
                        <div class="icon-wrap">
                        <i class="las la-shopping-cart"></i>

                            <div class="count" x-text="$store.state.cartQuantity">{{ $cart->toArray()['quantity'] }}</div>
                        </div>
                    </a>
                    <div class="header-user-group">
                        <div class="user-avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->username ?? 'username' }}</div>
                            <div class="user-balance">₺{{ number_format(auth()->user()->balance ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <button onclick="window.location.href = '{{ route('account.dashboard.balance') }}'" class="btn-add-balance" title="{{ trans('storefront::layouts.add_balance') }}">
                        <div class="balance-btn-icon">
                            <i class="las la-plus text-success"></i>
                        </div>
                        <div class="balance-btn-text">
                            <span class="balance-text-top text-success" style="white-space: normal;text-align: center;line-height: initial">{{ trans('storefront::layouts.add_balance') }}</span>
                        </div>
                    </button>
                    <div class="header-user-menu" x-data="{ userMenuOpen: false }">
                        <button
                            class="user-menu-toggle"
                            @click="userMenuOpen = !userMenuOpen"
                            :class="{ active: userMenuOpen }"
                        >
                            <i class="las la-bars"></i>
                        </button>
                        <div
                            class="user-menu-dropdown"
                            x-show="userMenuOpen"
                            x-transition
                            @click.away="userMenuOpen = false"
                            x-cloak
                        >
                            <div class="user-menu-header">
                                <div class="user-menu-avatar">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="user-menu-info">
                                    <div class="user-menu-name">{{ auth()->user()->username ?? 'username' }}</div>
                                    <div class="user-menu-email">₺{{ number_format(auth()->user()->balance ?? 0, 2) }}</div>
                                </div>
                            </div>
                            <div class="user-menu-links">
                            <a href="{{ route('account.dashboard.index') }}" class="user-menu-link">
                                <i class="las la-tachometer-alt"></i>
                                <span>{{ trans('storefront::account.pages.dashboard') }}</span>
                            </a>
                            <a href="{{ route('account.profile.edit') }}" class="user-menu-link">
                                <i class="las la-user"></i>
                                <span>{{ trans('storefront::account.pages.my_profile') }}</span>
                            </a>
                            <a href="{{ route('account.wishlist.index') }}" class="user-menu-link">
                                <i class="lar la-heart"></i>
                                <span>{{ trans('storefront::account.pages.my_wishlist') }}</span>
                            </a>
                            <a href="{{ route('account.orders.index') }}" class="user-menu-link">
                                <i class="las la-shopping-bag"></i>
                                <span>{{ trans('storefront::account.pages.my_orders') }}</span>
                            </a>

                            <a href="{{ route('logout') }}" class="user-menu-link text-danger">
                                    <i class="las la-sign-out-alt"></i>
                                    <span>{{ trans('storefront::account.pages.logout') }}</span>
                                </a>
                        </div>

                        </div>
                    </div>
                    @endauth

                    @guest
                    <a href="{{ route('register') }}" class="header-column-right-item header-register">
                        <div class="btn-register">
                            <span>{{ trans('user::auth.register') }}</span>
                        </div>
                    </a>
                    <a href="{{ route('login') }}" class="header-column-right-item header-login">
                        <div class="btn-login">
                            <span>{{ trans('user::auth.login') }}</span>
                        </div>
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <div class="header-mobile-top-row mobile-only">
        <div class="container-fluid">
            <div class="mobile-top-main d-flex justify-content-between align-items-center position-relative">
                <div class="mobile-top-left d-flex align-items-center">
                    <div class="sidebar-menu-icon-wrap" @click="$store.layout.openSidebarMenu()" x-show="!mobileSearchOpen" x-transition>
                        <div class="sidebar-menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="24px" height="24px">
                                <path d="M 3 9 A 1.0001 1.0001 0 1 0 3 11 L 47 11 A 1.0001 1.0001 0 1 0 47 9 L 3 9 z M 3 24 A 1.0001 1.0001 0 1 0 3 26 L 47 26 A 1.0001 1.0001 0 1 0 47 24 L 3 24 z M 3 39 A 1.0001 1.0001 0 1 0 3 41 L 47 41 A 1.0001 1.0001 0 1 0 47 39 L 3 39 z"></path>
                            </svg>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" class="header-logo" x-show="!mobileSearchOpen" x-transition>
                        @if (is_null($logo))
                            <h3>{{ setting('store_name') }}</h3>
                        @else
                            <img src="{{ $logo }}" alt="{{ setting('store_name') ?? 'Logo' }}">
                        @endif
                    </a>
                </div>

                <div class="mobile-search-area-expanded" x-show="mobileSearchOpen" x-transition>
                    @include('storefront::public.layouts.header.header_search')
                </div>

                <div class="mobile-top-right d-flex align-items-center">
                    <div class="header-dark-mode" x-show="!mobileSearchOpen" x-transition>
                        <div class="icon-wrap" @click="$store.layout.toggleDarkMode()">
                            <i class="las la-moon"></i>
                        </div>
                    </div>

                    <div class="header-search-toggle">
                        <button class="search-toggle-btn" @click="mobileSearchOpen = !mobileSearchOpen">
                            <i class="las" :class="mobileSearchOpen ? 'la-times' : 'la-search'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="header-mobile-bottom-row mobile-only">
        @auth
        <div class="mobile-bottom-auth d-flex justify-content-between align-items-center w-100">
            <div class="mobile-user-info d-flex align-items-center">
                <div class="user-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->username ?? 'username' }}</div>
                    <div class="user-balance">₺{{ number_format(auth()->user()->balance ?? 0, 2) }}</div>
                </div>
            </div>

            <div class="mobile-user-actions d-flex align-items-center">
                <a href="{{ route('account.dashboard.balance') }}" class="btn-add-balance mobile">
                    <span class="balance-text-line text-success" style="display: inline-block; white-space: normal;text-align: center">{{ trans('storefront::layouts.add_balance') }}</span>
                </a>

                <div class="header-user-menu" x-data="{ userMenuOpen: false }">
                    <button class="user-menu-toggle" @click="userMenuOpen = !userMenuOpen">
                        <i class="las la-bars"></i>
                    </button>

                    <div class="user-menu-dropdown" x-show="userMenuOpen" x-transition @click.away="userMenuOpen = false" x-cloak>
                        <div class="user-menu-header">
                            <div class="user-menu-avatar">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="user-menu-info">
                                <div class="user-menu-name">{{ auth()->user()->username }}</div>
                                <div class="user-menu-email">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="user-menu-balance">
                                <div class="balance-label">{{ trans('storefront::layouts.balance') }}</div>
                                <div class="balance-amount">₺{{ number_format(auth()->user()->balance ?? 0, 2) }}</div>
                            </div>
                        </div>

                        <div class="user-menu-links">
                            <a href="{{ route('account.dashboard.index') }}" class="user-menu-link">
                                <i class="las la-tachometer-alt"></i>
                                <span>{{ trans('storefront::account.pages.dashboard') }}</span>
                            </a>
                            <a href="{{ route('account.profile.edit') }}" class="user-menu-link">
                                <i class="las la-user"></i>
                                <span>{{ trans('storefront::account.pages.my_profile') }}</span>
                            </a>
                            <a href="{{ route('account.wishlist.index') }}" class="user-menu-link">
                                <i class="lar la-heart"></i>
                                <span>{{ trans('storefront::account.pages.my_wishlist') }}</span>
                            </a>
                            <a href="{{ route('account.orders.index') }}" class="user-menu-link">
                                <i class="las la-shopping-bag"></i>
                                <span>{{ trans('storefront::account.pages.my_orders') }}</span>
                            </a>

                            <a href="{{ route('logout') }}" class="user-menu-link text-danger">
                                    <i class="las la-sign-out-alt"></i>
                                    <span>{{ trans('storefront::account.pages.logout') }}</span>
                                </a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        @endauth

        @guest
        <div class="mobile-bottom-guest d-flex justify-content-between align-items-center w-100">
            <div class="mobile-login-warning">
                <span>{{ trans('storefront::layouts.login_warning') }}</span>
            </div>

            <div class="mobile-auth-buttons d-flex">
                <a href="{{ route('login') }}" class="btn-mobile-login">
                    <span>{{ trans('user::auth.login') }}</span>
                </a>
                <a href="{{ route('register') }}" class="btn-mobile-register">
                    <span>{{ trans('user::auth.register') }}</span>
                </a>
            </div>
        </div>
        @endguest
        </div>
    </div>
</header>
