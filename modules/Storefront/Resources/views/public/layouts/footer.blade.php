<footer class="footer-wrap">
    <div class="container">
        <div class="footer">
            <div class="footer-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-5">
                        <div class="footer-links">
                            <h4 class="title">{{ trans('storefront::layouts.my_account') }}</h4>

                            <ul class="list-inline">
                                <li>
                                    <a href="{{ route('account.dashboard.index') }}">
                                        {{ trans('storefront::account.pages.dashboard') }}
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('account.orders.index') }}">
                                        {{ trans('storefront::account.pages.my_orders') }}
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('account.reviews.index') }}">
                                        {{ trans('storefront::account.pages.my_reviews') }}
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('account.profile.edit') }}">
                                        {{ trans('storefront::account.pages.my_profile') }}
                                    </a>
                                </li>

                                @auth
                                    <li>
                                        <a href="{{ route('logout') }}">
                                            {{ trans('storefront::account.pages.logout') }}
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>

                    @if ($footerMenuOne->isNotEmpty())
                        <div class="col-lg-3 col-md-5">
                            <div class="footer-links">
                                <h4 class="title">{{ setting('storefront_footer_menu_one_title') }}</h4>

                                <ul class="list-inline">
                                    @foreach ($footerMenuOne as $menuItem)
                                        <li>
                                            <a href="{{ $menuItem->url() }}" target="{{ $menuItem->target }}">
                                                {{ $menuItem->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-5">
                        <div class="footer-links">
                            <h4 class="title">
                                Sosyal Medya
                            </h4>

                            <div class="social-media-section">
                                <ul class="social-links">
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

                    @if ($footerMenuTwo->isNotEmpty())
                        <div class="col-lg-3 col-md-5">
                            <div class="footer-links">
                                <h4 class="title">{{ setting('storefront_footer_menu_two_title') }}</h4>

                                <ul class="list-inline">
                                    @foreach ($footerMenuTwo as $menuItem)
                                        <li>
                                            <a href="{{ $menuItem->url() }}" target="{{ $menuItem->target }}">
                                                {{ $menuItem->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if ($footerTags->isNotEmpty())
                        <div class="col-lg-4 col-md-7">
                            <div class="footer-links footer-tags">
                                <h4 class="title">{{ trans('storefront::layouts.tags') }}</h4>

                                <ul class="list-inline">
                                    @foreach ($footerTags as $footerTag)
                                        <li>
                                            <a href="{{ $footerTag->url() }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.16989 15.3L8.69989 19.83C10.5599 21.69 13.5799 21.69 15.4499 19.83L19.8399 15.44C21.6999 13.58 21.6999 10.56 19.8399 8.69005L15.2999 4.17005C14.3499 3.22005 13.0399 2.71005 11.6999 2.78005L6.69989 3.02005C4.69989 3.11005 3.10989 4.70005 3.00989 6.69005L2.76989 11.69C2.70989 13.04 3.21989 14.35 4.16989 15.3Z"
                                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M9.5 12C10.8807 12 12 10.8807 12 9.5C12 8.11929 10.8807 7 9.5 7C8.11929 7 7 8.11929 7 9.5C7 10.8807 8.11929 12 9.5 12Z"
                                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>

                                                {{ $footerTag->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-5 col-md-8">
                        <div class="contact-us">
                            <h4 class="title">
                                {{ trans('storefront::layouts.contact_us') }}
                            </h4>

                            <div class="contact-options">
                                <div class="contact-option">
                                    <i class="las la-headset"></i>
                                    <span>{{ setting('contact_live_support_text', 'Canlı Desteğe Bağlan') }}</span>
                                </div>

                                @if (setting('store_whatsapp') && !setting('store_whatsapp_hide'))
                                    <div class="contact-option whatsapp-option">
                                        <i class="lab la-whatsapp"></i>
                                        <div class="contact-details">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('store_whatsapp')) }}" target="_blank" class="whatsapp-link">
                                                {{ setting('store_whatsapp') }}
                                            </a>
                                            <span class="contact-note">{{ setting('contact_whatsapp_note', 'Yalnızca WhatsApp') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if (setting('store_phone') && !setting('store_phone_hide'))
                                    <div class="contact-option phone-option">
                                        <i class="las la-phone"></i>
                                        <div class="contact-details">
                                            <a href="tel:{{ setting('store_phone') }}" class="phone-link">
                                                {{ setting('store_phone') }}
                                            </a>
                                            <span class="contact-note">{{ setting('contact_phone_note', 'Yalnızca Bildirim Gönderimi') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if (setting('store_email') && !setting('store_email_hide'))
                                    <div class="contact-option">
                                        <i class="las la-envelope"></i>
                                        <a href="mailto:{{ setting('store_email') }}" class="email-link">
                                            {{ setting('store_email') }}
                                        </a>
                                    </div>
                                @endif

                                @if (setting('storefront_address'))
                                    <div class="contact-option">
                                        <i class="las la-map"></i>
                                        <span>{{ setting('storefront_address') }}</span>
                                    </div>
                                @endif

                                @if (setting('contact_help_center_enabled'))
                                    <div class="contact-option">
                                        <i class="las la-crosshairs"></i>
                                        <div class="contact-details">
                                            <a href="{{ setting('contact_help_center_url', '#') }}" target="_blank" class="help-center-link">
                                                {{ setting('contact_help_center_text', 'Yardım Merkezi') }}
                                            </a>
                                            <span class="contact-description">{{ setting('contact_help_center_description') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if (setting('contact_support_request_enabled'))
                                    <div class="contact-option">
                                        <i class="las la-file-alt"></i>
                                        <a href="{{ setting('contact_support_request_url', '#') }}" target="_blank" class="support-request-link">
                                            {{ setting('contact_support_request_text', 'Destek Talebi Oluştur') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (setting('payment_methods_enabled') && setting('payment_methods_items'))
                <div class="footer-payment-methods">
                    <div class="container">
                        <div class="row">
                            <div class="col-18">
                                @if (setting('payment_methods_title'))
                                    <h4 class="payment-methods-title">{{ setting('payment_methods_title') }}</h4>
                                @endif
                                
                                <div class="payment-methods-slider" x-data="FooterPaymentMethods">
                                    <div class="swiper payment-methods-swiper">
                                        <div class="swiper-wrapper">
                                            @php
                                                $paymentMethods = setting('payment_methods_items', []);
                                                if (!is_array($paymentMethods)) {
                                                    $paymentMethods = [];
                                                }
                                            @endphp
                                            
                                            @foreach($paymentMethods as $method)
                                                @if (!empty($method['name']))
                                                    <div class="swiper-slide">
                                                        <div class="payment-method-item">
                                                            @if (!empty($method['file_id']))
                                                                @php
                                                                    $file = \Modules\Media\Entities\File::find($method['file_id']);
                                                                @endphp
                                                                @if ($file)
                                                                    <img src="{{ $file->path }}" alt="{{ $method['name'] }}" loading="lazy">
                                                                @else
                                                                    <span class="payment-method-name">{{ $method['name'] }}</span>
                                                                @endif
                                                            @else
                                                                <span class="payment-method-name">{{ $method['name'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (setting('legal_info_enabled') && (setting('legal_provider_info') || setting('legal_representative_info') || setting('legal_company_info')))
                <div class="footer-legal">
                    <div class="row">
                        @if (setting('legal_provider_info'))
                            <div class="col-lg-6 col-md-6">
                                <div class="legal-card">
                                    <h4 class="legal-title">{{ setting('legal_provider_title') }}</h4>
                                    <h5 class="legal-subtitle">{{ setting('legal_provider_subtitle') }}</h5>
                                    <div class="legal-content">
                                        {!! nl2br(e(setting('legal_provider_info'))) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (setting('legal_representative_info'))
                            <div class="col-lg-6 col-md-6">
                                <div class="legal-card">
                                    <h4 class="legal-title">{{ setting('legal_representative_title') }}</h4>
                                    <h5 class="legal-subtitle">{{ setting('legal_representative_subtitle') }}</h5>
                                    <div class="legal-content">
                                        {!! nl2br(e(setting('legal_representative_info'))) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (setting('legal_company_info'))
                            <div class="col-lg-6 col-md-6">
                                <div class="legal-card">
                                    <h4 class="legal-title">{{ setting('legal_company_title') }}</h4>
                                    <h5 class="legal-subtitle">{{ setting('legal_company_subtitle') }}</h5>
                                    <div class="legal-content">
                                        {!! nl2br(e(setting('legal_company_info'))) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="footer-bottom">
                <div class="row align-items-center text-center">
                    <div class="col-md-18">
                        <div class="footer-text">
                            {!! $copyrightText !!}
                        </div>
                    </div>

                    <!-- @if ($acceptedPaymentMethodsImage->exists)
                        <div class="col-md-9 col-sm-18">
                            <div class="footer-payment">
                                <img src="{{ $acceptedPaymentMethodsImage->path }}" alt="Accepted payment methods"
                                    loading="lazy">
                            </div>
                        </div>
                    @endif -->
                </div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
    <script type="module">
        // Contact option click handlers
        $('.contact-option').on('click', function() {
            const link = $(this).find('a');
            if (link.length > 0) {
                window.open(link.attr('href'), link.attr('target') || '_self');
            }
        });


    </script>
@endpush
