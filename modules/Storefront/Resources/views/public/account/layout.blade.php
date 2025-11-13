@extends('storefront::public.layout')

@section('breadcrumb')
    @if (request()->routeIs('account.dashboard.index'))
        <li class="active">{{ trans('storefront::account.pages.my_account') }}</li>
    @else
        <li><a href="{{ route('account.dashboard.index') }}">{{ trans('storefront::account.pages.my_account') }}</a></li>
    @endif

    @yield('account_breadcrumb')
@endsection

@section('content')
    <section class="account-wrap">
        <div class="container">
            <div class="account-wrap-inner">
                <div class="account-left">
                    <ul class="account-sidebar list-inline d-flex flex-column">
                        <li class="{{ request()->routeIs('account.dashboard.index') ? 'active' : '' }}">
                            <a href="{{ route('account.dashboard.index') }}">
                                <i class="las la-tachometer-alt"></i>

                                {{ trans('storefront::account.pages.dashboard') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.dashboard.balance') ? 'active' : '' }}">
                            <a href="{{ route('account.dashboard.balance') }}">
                                <i class="las la-wallet"></i>

                                Bakiye Yükle
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.money-check') ? 'active' : '' }}">
                            <a href="{{ route('account.money-check') }}">
                                <i class="las la-money-bill"></i>

                                Para Çekimi
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.orders.index') ? 'active' : '' }}">
                            <a href="{{ route('account.orders.index') }}">
                                <i class="las la-cart-arrow-down"></i>

                                {{ trans('storefront::account.pages.my_orders') }}
                            </a>
                        </li>



                        <li class="{{ request()->routeIs('account.downloads.index') ? 'active' : '' }}">
                            <a href="{{ route('account.downloads.index') }}">
                                <i class="las la-download"></i>

                                {{ trans('storefront::account.pages.my_downloads') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.wishlist.index') ? 'active' : '' }}">
                            <a href="{{ route('account.wishlist.index') }}">
                                <i class="lar la-heart"></i>

                                {{ trans('storefront::account.pages.my_wishlist') }}

                                <span class="count" x-text="$store.state.wishlistCount"></span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.reviews.index') ? 'active' : '' }}">
                            <a href="{{ route('account.reviews.index') }}">
                                <i class="las la-comment"></i>

                                {{ trans('storefront::account.pages.my_reviews') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.addresses.index') ? 'active' : '' }}">
                            <a href="{{ route('account.addresses.index') }}">
                                <i class="las la-address-book"></i>

                                {{ trans('storefront::account.pages.my_addresses') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('account.profile.edit') }}">
                                <i class="las la-user-circle"></i>

                                {{ trans('storefront::account.pages.my_profile') }}
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('account.reference') ? 'active' : '' }}">
                            <a href="{{ route('account.reference') }}">
                                <i class="las la-plus"></i>

                                Referans Sistemi
                            </a>
                        </li>

                        @if(auth()->check() && auth()->user()->is_vendor)
                            <!-- VENDOR MENÜSÜ -->
                            <li class="sidebar-divider">
                                <span>MAĞAZA YÖNETİMİ</span>
                            </li>

                            <li class="{{ request()->routeIs('account.vendor.listings.index') ? 'active' : '' }}">
                                <a href="{{ url('account/vendor/listings') }}">
                                    <i class="las la-box"></i>
                                    Ürünlerim
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('account.vendor.listings.create') ? 'active' : '' }}">
                                <a href="{{ url('account/vendor/listings/create') }}">
                                    <i class="las la-plus-circle"></i>
                                    Ürün Ekle
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('account.vendor.orders') ? 'active' : '' }}">
                                <a href="{{ url('account/vendor/orders') }}">
                                    <i class="las la-shopping-cart"></i>
                                    Siparişlerim
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('account.vendor.earnings') ? 'active' : '' }}">
                                <a href="{{ url('account/vendor/earnings') }}">
                                    <i class="las la-coins"></i>
                                    Kazançlarım
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('account.vendor.shop') ? 'active' : '' }}">
                                <a href="{{ url('account/vendor/shop') }}">
                                    <i class="las la-store"></i>
                                    Mağaza Ayarları
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="las la-sign-out-alt"></i>

                                {{ trans('storefront::account.pages.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="account-right">
                    <div class="panel-wrap">
                        @yield('panel')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/main.scss'
    ])

    <style>
        /* Vendor Menü Bölümü */
        .account-sidebar .sidebar-divider {
            padding: 15px 20px 10px;
            margin-top: 15px;
            border-top: 2px solid #e0e0e0;
        }
        .account-sidebar .sidebar-divider span {
            font-size: 12px;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
@endpush
