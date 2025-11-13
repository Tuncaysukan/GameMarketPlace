<!DOCTYPE html>
<html lang="{{ locale() }}">
    <head>
        <base href="{{ config('app.url') }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

        <title>
            @hasSection('title')
                @yield('title') - {{ setting('store_name') }}
            @else
                {{ setting('store_name') }}
            @endif
        </title>

        @stack('meta')
        @PWA

        <link rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ font_url(setting('storefront_display_font', 'Poppins')) }}" rel="stylesheet">

        @include('storefront::public.partials.variables')

        @vite([
            'modules/Storefront/Resources/assets/public/sass/app.scss',
            'modules/Storefront/Resources/assets/public/js/app.js',
            'modules/Storefront/Resources/assets/public/js/main.js'
        ])

        @stack('styles')

        {!! setting('custom_header_assets') !!}

        <script>
            window.Veles = {
                baseUrl: '{{ config('app.url') }}',
                rtl: {{ is_rtl() ? 'true' : 'false' }},
                storeName: '{{ setting('store_name') }}',
                storeLogo: '{{ $logo }}',
                currency: '{{ currency() }}',
                locale: '{{ locale() }}',
                loggedIn: {{ auth()->check() ? 'true' : 'false' }},
                csrfToken: '{{ csrf_token() }}',
                cart: {!! $cart !!},
                wishlist: {!! $wishlist !!},
                compareList: {!! $compareList !!},
                langs: {
                    'storefront::storefront.something_went_wrong': '{{ trans('storefront::storefront.something_went_wrong') }}',
                    'storefront::layouts.more_results': '{{ trans('storefront::layouts.more_results') }}'
                },
            };
        </script>

        {!! $schemaMarkup->toScript() !!}

        @stack('globals')

        <script type="module">
            Alpine.start();
        </script>

        @routes

        <style>

.header-wrap {
    background: var(--card-bg);
}

.header-desktop-row {
    z-index: 100;
}

.header-wrap-inner {
    padding: 0;
    background: #ffffff;
    border-bottom: 1px solid #e9ecef;
}

.header-wrap-inner.sticky {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 1000 !important;
    opacity: 0;
    transform: translateY(-100%);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.header-wrap-inner.sticky.show {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.header-column-left {
    display: flex;
    align-items: center;
    padding-left: 0;
    margin-bottom: 7px;
}

@media (min-width: 769px){
    .header-column-left {
        gap: 15px;
    }
}

.header-logo h3 {
    color: #5865F2;
    font-weight: 600;
    margin: 0;
    font-size: 26px;
    text-decoration: none;
    display: flex;
    align-items: center;
    height: 50px;
    line-height: 50px;
}

.header-logo img {
    height: 50px;
}

.sidebar-menu-icon-wrap {
    display: none;
    padding-right: 0!important;
}

.header-column-center {
    flex: 1;
    max-width: 500px;
    margin: 0 15px;
}

.header-search-wrap {
    width: 100%;
}

.header-search-wrap .header-search {
    width: 100%;
}

.header-search-wrap .header-search-lg {
    display: flex;
    align-items: center;
    background: #f3f3f4;
    padding: 0;
    height: 50px;
    border: 1px solid #e0e0e0;
}

.header-search-wrap .header-search-lg .search-input {
    flex: 1;
    background: transparent;
    border: none;
    height: 48px;
    padding: 0 20px;
    font-size: 14px;
    color: #333;
}

.header-search-wrap .header-search-lg .search-input:focus {
    outline: none;
}

.header-search-wrap .header-search-lg .search-input::placeholder {
    color: #999;
}

.header-search-wrap .header-search-lg .search-btn {
    background: transparent !important;
    border: none;
    padding: 0 15px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.header-search-wrap .header-search-lg .search-btn i {
    color: #999;
    font-size: 18px;
}

.header-column-right {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: flex-end;
    margin-left: auto;
    margin-bottom: 7px;
}

.header-dark-mode .icon-wrap {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.header-dark-mode .icon-wrap:hover {
    background: #f0f0f0;
}

.header-dark-mode .icon-wrap i {
    font-size: 20px;
    color: #666;
}

.header-cart .icon-wrap {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #d8deff;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
    position: relative;
    font-size: 22px;
    box-shadow: 0 2px 8px #7c3aed26;
}

.header-cart .icon-wrap:hover {
    background: #3d5afe;
}

.header-cart .icon-wrap:hover i{
    color: #d8deff;
}

.header-cart .icon-wrap i{
    color: #3d5afe;
}

.header-cart .icon-wrap i {
    width: 22px;
    height: 22px;
}

.header-cart .icon-wrap .count {
    position: absolute;
    top: 0;
    right: 0;
    background: #ff4757;
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-add-listing {
    margin-left: 5px;
}

.header-add-listing .btn-add-listing {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #10b981;
    color: #ffffff;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    height: 40px;
}

.header-add-listing .btn-add-listing:hover {
    background: #0ea271;
    transform: translateY(-1px);
}

.header-add-listing .btn-add-listing i {
    font-size: 16px;
}

.header-register .btn-register {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #10b981;
    color: #ffffff;
    padding: 0 24px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    height: 50px;
    span{
        color: var(--bs-light);
    }
}

.header-register .btn-register:hover {
    background: #0ea271;
    transform: translateY(-1px);
}

.header-register .btn-register i {
    font-size: 16px;
}

.header-login .btn-login {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #5865F2;
    color: #ffffff;
    padding: 0 24px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    height: 50px;
    span{
        color: var(--bs-light);
    }
}

.header-login .btn-login:hover {
    background: #4752C4;
    transform: translateY(-1px);
}

.header-login .btn-login i {
    font-size: 16px;
}

.header-wrap .container .d-flex {
    gap: 15px;
}

.top-nav-wrap {
    padding: 0;
    font-size: 13px;
}

.navigation-wrap {
    background: var(--card-bg);
}

.top-nav-left .nav-link {
    color: #666;
    padding: 5px 12px;
    text-decoration: none;
    font-size: 13px;
}

.top-nav-left .nav-link:hover {
    color: #333;
}

.top-nav-center .welcome-text {
    color: #333;
    font-size: 13px;
}

.top-nav-right .social-links {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    gap: 10px;
}

.top-nav-right .social-links li {
    display: inline-block;
}

.top-nav-right .social-link-item {
    color: #666;
    font-size: 16px;
}

.top-nav-right .social-link-item:hover {
    color: #333;
}

@media (max-width: 991px) {
    .ltr .header-search-wrap {
        margin: unset;
    }
    .header-column-center {
        margin: 0 15px;
    }
    .header-search-wrap {
        padding: 0;
    }
    .mobile-search-area-expanded{
        right: 50px!important;
    }
    .sidebar-menu-icon-wrap {
        display: flex;
        width: 50px;
        height: 50px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .sidebar-menu-icon svg {
        width: 24px;
        height: 24px;
    }
}

@media (max-width: 768px) {
    .header-desktop-row {
        display: none;
    }
    .header-add-listing,
    .header-register span,
    .header-login span {
        display: none;
    }

    .header-register .btn-register,
    .header-login .btn-login {
        padding: 8px 12px;
    }

    .header-column-center {
        display: none;
    }
}
.header-search .header-search-lg .search-button-wrapper .btn-search{
    background: transparent !important;
}
.header-search .header-search-lg .search-button-wrapper .btn-search i {
    color: #999;
}

.header-user-group {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 15px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f0f0;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 16px;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1;
}

.user-balance {
    font-size: 12px;
    color: #10b981;
    font-weight: 700;
    line-height: 1;
}

.btn-add-balance {
    width: 90px;
    height: 50px;
    display: flex;
    color: rgb(97, 208, 128);
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    background-color: rgb(223, 246, 203);
    box-shadow: 0 2px 8px #7c3aed26;
    transition: background 0.3s;
    gap: 8px;
}

.btn-add-balance:hover {
    background-color: rgb(97, 208, 128);
    color: rgb(223, 246, 203);
    transform: translateY(-1px);
}

.balance-btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.balance-btn-icon i {
    font-size: 16px;
}

.balance-btn-text {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    line-height: 1;
    gap: 1px;
}

.balance-text-top,
.balance-text-bottom {
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

.header-user-menu {
    position: relative;
}

.user-menu-toggle {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: 2px solid #d8deff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    background: #d8deff;

}

.user-menu-toggle:hover {
    background: #5865F2;
}

.user-menu-toggle i {
    font-size: 20px;
    color: #5865F2;
}
.user-menu-toggle:hover i {
    font-size: 20px;
    color: #d8deff!important;
}

.user-menu-toggle.active i {
    color: #d8deff;
}

.user-menu-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    overflow: hidden;
    margin-top: 10px;
}

.user-menu-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    color: #fff;
}

.user-menu-avatar {
    width: 50px;
    height: 50px;
    overflow: hidden;
    flex-shrink: 0;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.user-menu-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-menu-avatar .avatar-placeholder {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 18px;
}

.user-menu-info {
    flex: 1;
}

.user-menu-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.user-menu-email {
    font-size: 13px;
    opacity: 0.9;
}

.user-menu-balance {
    text-align: right;
}

.balance-label {
    font-size: 11px;
    opacity: 0.8;
    margin-bottom: 2px;
}

.balance-amount {
    font-size: 16px;
    font-weight: 700;
}

.user-menu-links {
    padding: 15px 0;
}

.user-menu-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    margin: 8px;
    border-radius: 6px;
    background: rgb(249, 251, 252);
    transition: all 0.3s;
}

.user-menu-link:last-child {
    background: rgb(218, 8, 8);
    color: #fff;
    i,span {
        color: #fff;
    }
}

.user-menu-link:hover {
    background: #f8f9fa;
    color: #5865F2;
    text-decoration: none;
}

.user-menu-link i {
    font-size: 18px;
    width: 20px;
    color: #666;
}

.user-menu-link:hover i {
    color: #5865F2;
}

.user-menu-link span {
    font-size: 14px;
    font-weight: 500;
}

.user-menu-logout {
    border-top: 1px solid #f0f0f0;
    padding: 0 20px;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 0;
    background: none;
    border: none;
    color: #dc3545;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.logout-btn:hover {
    color: #c82333;
}

.logout-btn i {
    font-size: 18px;
    width: 20px;
}

.mobile-only {
    display: none;
}

.mobile-search-overlay {
    display: none;
}




.header-mobile-top-row {
    padding: 10px 4px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 1000;
    background: var(--card-bg);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.mobile-top-main {
    width: 100%;
}

.mobile-search-area-expanded {
    position: absolute;
    left: 0;
    right: 40px;
    top: 0;
    bottom: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    background: #fff;
    padding-right: 2px
}

.header-search-wrap-parent{
    width: 100%;
}

.mobile-search-area-expanded .header-search-wrap {
    width: 100%;
    position: relative;
}

.mobile-search-area-expanded .header-search-lg {
    width: 100%;
}

.mobile-search-area-expanded .search-input-wrapper {
    width: 100%;
}

.mobile-search-area-expanded .search-input-wrapper input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
}

.mobile-search-area-expanded .search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 6px;
    margin-top: 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.mobile-search-area-expanded .search-button-wrapper {
    display: none;
}

.mobile-top-left .sidebar-menu-icon-wrap {
    margin-right: 15px;
}

.mobile-top-left .sidebar-menu-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #d8deff;
    border: 2px solid #d8deff;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-top-left .sidebar-menu-icon:hover {
    background: #3d5afe;
    border-color: #3d5afe;
}

.mobile-top-left .sidebar-menu-icon svg {
    width: 20px;
    height: 20px;
}

.mobile-top-left .sidebar-menu-icon svg path {
    fill: #3d5afe;
}

.mobile-top-left .sidebar-menu-icon:hover svg path {
    fill: #d8deff;
}

.mobile-top-right {
    gap: 10px;
}

.header-dark-mode .icon-wrap {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #d8deff;
    border: 2px solid #d8deff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-dark-mode .icon-wrap:hover {
    background: #3d5afe;
    border-color: #3d5afe;
}

.header-dark-mode .icon-wrap i {
    font-size: 18px;
    color: #3d5afe;
}

.header-dark-mode .icon-wrap:hover i {
    color: #d8deff;
}

.header-search-toggle .search-toggle-btn {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #d8deff;
    border: 2px solid #d8deff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}
footer {
    background-color: var(--footer-bg)!important;
}
.dark-mode .subscribe .subscribe-text .title, .subscribe .subscribe-text .sub-title {
    color: #fff;
}
.dark-mode .header-search-wrap .header-search-lg{
    background: unset;
    border-color: var(--color-gray);
}
.dark-mode .header-search .header-search-lg .search-input-wrapper .search-input.focused{
    color: #fff;
}
.dark-mode .cookie-bar .cookie-bar-action .btn-decline{
    background: unset;
}
.dark-mode .cookie-bar .cookie-bar-action .btn-accept{
    color: unset;
}
.dark-mode .sidebar-cart-middle {
    border-color: var(--color-gray-lite);
}

.dark-mode .form-control:disabled{
    background: unset;
}
.dark-mode .product-card .product-name h6, .product-card .product-name .h6{
    background: unset;
}
.dark-mode .product-card .product-card-actions .btn{
    transition: var(--transition-150);
    background: var(--color-primary-alpha-10);
    border: 1px solid var(--color-primary-alpha-15);
    svg{
        fill: var(--color-primary)
    }
}
.dark-mode .product-details-info .btn-add-to-cart{
    color: unset;
}
.dark-mode .mega-menu .dropdown>.sub-menu>li>a{
    background: unset!important;
    color: #fff!important;
}
.dark-mode .mega-menu .dropdown>.sub-menu>li:hover{
    background: rgb(51, 51, 51)!important;
}
.dark-mode .btn-primary{
    color: #fff;
}
.dark-mode .sidebar-cart-bottom .sidebar-cart-actions .btn-view-cart{
    background: unset;
}
.dark-mode .sidebar-cart-bottom .sidebar-cart-actions .btn-checkout{
    color: unset;
}
.dark-mode .header-search-toggle .search-toggle-btn{
    background: #404040;
    border-color: #555;
    i {
        color: #c4b5fd;
    }
}

.dark-mode .table>:not(caption)>*>* {
    background: #404040;
    color: #fff;
}

.dark-mode .header-cart .icon-wrap i {
    color: #c4b5fd;
}

.dark-mode .header-cart .icon-wrap{
    background: #404040;
    border: 2px solid #555;
}

.dark-mode .mobile-top-left .sidebar-menu-icon{
    background: #404040;
    border-color: #555;
}

.dark-mode .mobile-top-left .sidebar-menu-icon svg path {
    fill: #c4b5fd;
}

.header-search-toggle .search-toggle-btn:hover {
    background: #3d5afe;
    border-color: #3d5afe;
}

.header-search-toggle .search-toggle-btn i {
    font-size: 18px;
    color: #3d5afe;
}

.header-search-toggle .search-toggle-btn:hover i {
    color: #d8deff;
}

.dark-mode .user-menu-toggle i {
    color: #c4b5fd;
}

.dark-mode .user-menu-toggle {
    background: #404040;
    border-color: #555;
    i {
        color: #c4b5fd;
    }
}

.dark-mode .user-menu-links{
    background: #404040;
    .user-menu-link{
        background: var(--card-bg);
        i {
            color: #c4b5fd;
        }
    }
    .user-menu-link:last-child{
        background: var(--color-red);
        i {
            color: #fff;
        }
    }
}

.user-menu-name, .user-menu-email{
    color: var(--bs-white);
}

.header-mobile-bottom-row {
    padding: 10px 4px;
    position: relative;
    z-index: 1;
}


.mobile-user-info {
    gap: 12px;
}

.mobile-user-info .user-avatar {
    width: 50px;
    height: 50px;
}

.mobile-user-info .avatar-placeholder {
    width: 50px;
    height: 50px;
    font-size: 14px;
}

.mobile-user-actions {
    gap: 10px;
}

.btn-add-balance.mobile {
    width: 60px;
    height: 50px;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgb(223, 246, 203);
    color: rgb(97, 208, 128);
    border: none;
    font-size: 10px;
    font-weight: 600;
    text-decoration: none;
    line-height: 1;
    gap: 1px;
}

.btn-add-balance.mobile:hover {
    background: rgb(97, 208, 128);
    color: rgb(223, 246, 203);
    text-decoration: none;
}

.balance-text-line {
    display: block;
    font-size: 12px;
    font-weight: 600;
}

.mobile-login-warning {
    color: #666;
    font-size: 14px;
}

.mobile-auth-buttons {
    gap: 10px;
}

.btn-mobile-login,
.btn-mobile-register {
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-mobile-login {
    color: #333;
    border: 1px solid #dee2e6;
}

.btn-mobile-login:hover {
    background: #e9ecef;
    color: #333;
}

.btn-mobile-register {
    background: #007bff;
    border: 1px solid #007bff;
    span {
        color: var(--bs-light);
    }
}

.btn-mobile-register:hover {
    background: #0056b3;
    color: #fff;
}

@media (max-width: 768px) {
    .header-desktop-row {
        display: none !important;
    }

    .mobile-only {
        display: flex !important;
    }

    .header-mobile-top-row,
    .header-mobile-bottom-row {
        display: flex !important;
    }

    .mobile-bottom-auth .user-menu-dropdown {
        width: 93vw;
        right: -1px;
    }

    .header-logo img {
        height: 38px;
    }

    body {
        padding-top: 70px !important;
    }

    .header-mobile-bottom-row {
        position: static !important;
        margin-top: 10px;
        padding-top: 0;
    }

}

@media (min-width: 769px) {
    .mobile-only {
        display: none !important;
    }

    .header-desktop-row {
        display: flex !important;
    }
}

@media (max-width: 480px) {
    .header-logo img {
        height: 38px;
    }
    .header-user-group {
        display: none;
    }
}
@media screen and (max-width: 991px) {
    .bottom-navigation-wrap {
        border-top: 1px solid var(--border-color);
    }
    .bottom-navigation-wrap.d-lg-none > div > ul > li > a > svg{
        fill: var(--color-black-20);
    }
}
.nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
    color: var(--color-black);
    background-color: transparent;
    border-color: var(--bs-nav-tabs-link-active-border-color);
}
.home-section-wrap{
    margin-top: 0;
}
        </style>
    </head>

    <body
        dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"
        class="page-template {{ is_rtl() ? 'rtl' : 'ltr' }}"
        data-theme-color="{{ $themeColor->toHexString() }}"
    >
        <div x-data="App" class="wrapper">
            @include('storefront::public.layouts.top_nav')
            @include('storefront::public.layouts.header')
            @include('storefront::public.layouts.navigation')
            @include('storefront::public.layouts.breadcrumb')

            @yield('content')

            @include('storefront::public.home.sections.newsletter_subscription')
            @include('storefront::public.layouts.footer')

            <div
                class="overlay"
                :class="{ active: $store.layout.overlay }"
                @click="hideOverlay"
            >
            </div>

            @include('storefront::public.layouts.sidebar_menu')
            @include('storefront::public.layouts.localization')

            @if (!request()->routeIs('checkout.create'))
                @include('storefront::public.layouts.sidebar_cart')
            @endif

            @include('storefront::public.layouts.alert')
            @include('storefront::public.layouts.newsletter_popup')
            @include('storefront::public.layouts.cookie_bar')
            @include('storefront::public.layouts.scroll_to_top')
        </div>

        @stack('pre-scripts')
        @stack('scripts')

        {!! setting('custom_footer_assets') !!}
    </body>
</html>
