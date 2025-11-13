<div class="row">
    <div class="col-md-8">
        <div class="box-content clearfix">
            {{ Form::text('translatable[store_name]', trans('setting::attributes.translatable.store_name'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('translatable[store_tagline]', trans('setting::attributes.translatable.store_tagline'), $errors, $settings, ['rows' => 2]) }}
            {{ Form::text('store_phone', trans('setting::attributes.store_phone'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('store_whatsapp', trans('setting::attributes.store_whatsapp'), $errors, $settings) }}
            {{ Form::text('store_email', trans('setting::attributes.store_email'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('store_address_1', trans('setting::attributes.store_address_1'), $errors, $settings) }}
            {{ Form::text('store_address_2', trans('setting::attributes.store_address_2'), $errors, $settings) }}
            {{ Form::text('store_city', trans('setting::attributes.store_city'), $errors, $settings) }}
            {{ Form::select('store_country', trans('setting::attributes.store_country'), $errors, $countries, $settings) }}

            <div class="store-state input">
                {{ Form::text('store_state', trans('setting::attributes.store_state'), $errors, $settings) }}
            </div>

            <div class="store-state select hide">
                {{ Form::select('store_state', trans('setting::attributes.store_state'), $errors, [], $settings) }}
            </div>

            {{ Form::text('store_zip', trans('setting::attributes.store_zip'), $errors, $settings) }}
        </div>

        <div class="box-content clearfix">
            <h4 class="section-title">{{ trans('setting::settings.form.privacy_settings') }}</h4>

            {{ Form::checkbox('store_phone_hide', trans('setting::attributes.store_phone_hide'), trans('setting::settings.form.hide_store_phone'), $errors, $settings) }}
            {{ Form::checkbox('store_whatsapp_hide', trans('setting::attributes.store_whatsapp_hide'), trans('setting::settings.form.hide_store_whatsapp'), $errors, $settings) }}
            {{ Form::checkbox('store_email_hide', trans('setting::attributes.store_email_hide'), trans('setting::settings.form.hide_store_email'), $errors, $settings) }}
        </div>

        <div class="box-content clearfix">
            <h4 class="section-title">{{ trans('setting::settings.form.contact_settings') }}</h4>

            {{ Form::text('contact_live_support_text', trans('setting::attributes.contact_live_support_text'), $errors, $settings, ['placeholder' => 'Canlı Desteğe Bağlan']) }}
            {{ Form::text('contact_whatsapp_note', trans('setting::attributes.contact_whatsapp_note'), $errors, $settings, ['placeholder' => 'Yalnızca WhatsApp']) }}
            {{ Form::text('contact_phone_note', trans('setting::attributes.contact_phone_note'), $errors, $settings, ['placeholder' => 'Yalnızca Bildirim Gönderimi']) }}

            {{ Form::checkbox('contact_help_center_enabled', trans('setting::attributes.contact_help_center_enabled'), trans('setting::settings.form.enable_contact_help_center'), $errors, $settings) }}
            {{ Form::text('contact_help_center_text', trans('setting::attributes.contact_help_center_text'), $errors, $settings, ['placeholder' => 'Yardım Merkezi']) }}
            {{ Form::textarea('contact_help_center_description', trans('setting::attributes.contact_help_center_description'), $errors, $settings, ['rows' => 3]) }}
            {{ Form::text('contact_help_center_url', trans('setting::attributes.contact_help_center_url'), $errors, $settings, ['placeholder' => 'https://example.com/help']) }}

            {{ Form::checkbox('contact_support_request_enabled', trans('setting::attributes.contact_support_request_enabled'), trans('setting::settings.form.enable_contact_support_request'), $errors, $settings) }}
            {{ Form::text('contact_support_request_text', trans('setting::attributes.contact_support_request_text'), $errors, $settings, ['placeholder' => 'Destek Talebi Oluştur']) }}
            {{ Form::text('contact_support_request_url', trans('setting::attributes.contact_support_request_url'), $errors, $settings, ['placeholder' => 'https://example.com/support']) }}
        </div>

        <div class="box-content clearfix">
            <h4 class="section-title">{{ trans('setting::settings.form.legal_settings') }}</h4>

            {{ Form::checkbox('legal_info_enabled', trans('setting::attributes.legal_info_enabled'), trans('setting::settings.form.enable_legal_info'), $errors, $settings) }}

            <div class="legal-info-fields" style="{{ setting('legal_info_enabled') ? '' : 'display: none;' }}">
                {{ Form::text('translatable[legal_provider_title]', trans('setting::attributes.translatable.legal_provider_title'), $errors, $settings, ['placeholder' => 'YASAL BİLGİLENDİRME', 'value' => setting('translatable.legal_provider_title', 'YASAL BİLGİLENDİRME')]) }}
                {{ Form::text('translatable[legal_provider_subtitle]', trans('setting::attributes.translatable.legal_provider_subtitle'), $errors, $settings, ['placeholder' => 'Yer Sağlayıcı Bilgilendirmesi', 'value' => setting('translatable.legal_provider_subtitle', 'Yer Sağlayıcı Bilgilendirmesi')]) }}
                {{ Form::textarea('legal_provider_info', trans('setting::attributes.legal_provider_info'), $errors, $settings, ['rows' => 4, 'placeholder' => '']) }}
                
                {{ Form::text('translatable[legal_representative_title]', trans('setting::attributes.translatable.legal_representative_title'), $errors, $settings, ['placeholder' => 'YASAL & HUKUKİ TEMSİLCİ', 'value' => setting('translatable.legal_representative_title', 'YASAL & HUKUKİ TEMSİLCİ')]) }}
                {{ Form::text('translatable[legal_representative_subtitle]', trans('setting::attributes.translatable.legal_representative_subtitle'), $errors, $settings, ['value' => setting('translatable.legal_representative_subtitle')]) }}
                {{ Form::textarea('legal_representative_info', trans('setting::attributes.legal_representative_info'), $errors, $settings, ['rows' => 6, 'placeholder' => '']) }}
                
                {{ Form::text('translatable[legal_company_title]', trans('setting::attributes.translatable.legal_company_title'), $errors, $settings, ['placeholder' => 'İŞLETMECİ ŞİRKET', 'value' => setting('translatable.legal_company_title', 'İŞLETMECİ ŞİRKET')]) }}
                {{ Form::text('translatable[legal_company_subtitle]', trans('setting::attributes.translatable.legal_company_subtitle'), $errors, $settings, ['value' => setting('translatable.legal_company_subtitle')]) }}
                {{ Form::textarea('legal_company_info', trans('setting::attributes.legal_company_info'), $errors, $settings, ['rows' => 5, 'placeholder' => '']) }}
            </div>
        </div>

        <div class="box-content clearfix">
            <h4 class="section-title">{{ trans('setting::settings.form.payment_methods_settings') }}</h4>

            {{ Form::checkbox('payment_methods_enabled', trans('setting::attributes.payment_methods_enabled'), trans('setting::settings.form.enable_payment_methods'), $errors, $settings) }}

            <div class="payment-methods-fields" style="{{ setting('payment_methods_enabled') ? '' : 'display: none;' }}">
                <div class="payment-methods-container">
                    <div class="payment-methods-header">
                        <h5>{{ trans('setting::settings.form.payment_methods_items') }}</h5>
                        <button type="button" class="btn btn-primary btn-sm add-payment-method">
                            <i class="las la-plus"></i> {{ trans('setting::settings.form.add_payment_method') }}
                        </button>
                    </div>
                    
                    <div class="payment-methods-list">
                        @php
                            $paymentMethods = setting('payment_methods_items', []);
                            if (is_string($paymentMethods)) {
                                $paymentMethods = unserialize($paymentMethods);
                            }
                            if (!is_array($paymentMethods) || empty($paymentMethods)) {
                                $paymentMethods = [];
                            }
                        @endphp
                        
                        @foreach($paymentMethods as $index => $method)
                            @if (!empty($method['name']) || !empty($method['file_id']))
                                <div class="payment-method-item" data-index="{{ $index }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ trans('setting::attributes.payment_method_name') }}</label>
                                            <input type="text" 
                                                   name="payment_methods_items[{{ $index }}][name]" 
                                                   class="form-control payment-method-name" 
                                                   placeholder="VISA" 
                                                   value="{{ $method['name'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>{{ trans('setting::attributes.payment_method_logo') }} <small class="text-muted">(100x40px)</small></label>
                                        <div class="payment-method-image {{ !empty($method['file_id']) && isset($media[$method['file_id']]) ? 'has-image' : '' }}" data-payment-method-index="{{ $index }}" data-image-type="logo">
                                            @if (!empty($method['file_id']) && isset($media[$method['file_id']]))
                                                <img src="{{ $media[$method['file_id']]->path }}" alt="payment-method-logo">
                                                <input type="hidden" name="payment_methods_items[{{ $index }}][file_id]" value="{{ $method['file_id'] }}">
                                            @elseif (!empty($method['file_id']))
                                                <i class="fa fa-image"></i>
                                                <input type="hidden" name="payment_methods_items[{{ $index }}][file_id]" value="{{ $method['file_id'] }}">
                                            @else
                                                <i class="fa fa-image"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm remove-payment-method" style="cursor: pointer; z-index: 1000;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
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

<script>
$(document).ready(function() {
    // Legal info toggle
    $('input[name="legal_info_enabled"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('.legal-info-fields').slideDown();
        } else {
            $('.legal-info-fields').slideUp();
        }
    });

    // Payment methods toggle
    $('input[name="payment_methods_enabled"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('.payment-methods-fields').slideDown();
        } else {
            $('.payment-methods-fields').slideUp();
        }
    });

    // Add payment method
    $('.add-payment-method').on('click', function() {
        var index = $('.payment-method-item').length;
        var template = `
            <div class="payment-method-item" data-index="${index}">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ trans('setting::attributes.payment_method_name') }}</label>
                            <input type="text" name="payment_methods_items[${index}][name]" class="form-control payment-method-name" placeholder="VISA">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ trans('setting::attributes.payment_method_logo') }} <small class="text-muted">(100x60px)</small></label>
                            <div class="payment-method-image" data-payment-method-index="${index}" data-image-type="logo">
                                <i class="fa fa-image"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-payment-method">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('.payment-methods-list').append(template);
    });

    // Remove payment method
    $(document).on('click', '.remove-payment-method', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $item = $(this).closest('.payment-method-item');
        var index = $item.data('index');

        var inputsToRemove = $item.find('input[name*="payment_methods_items"]');
        inputsToRemove.remove();
        
        $('input[name*="payment_methods_items[' + index + ']"]').remove();
        $item.remove();
        
        if ($('.payment-method-item').length === 0) {
            $('.payment-methods-list').html('<p class="text-muted text-center">Henüz ödeme yöntemi eklenmemiş.</p>');
        }
        
        $('.payment-method-item').each(function(newIndex) {
            var $currentItem = $(this);
            var oldIndex = $currentItem.data('index');
            
            $currentItem.attr('data-index', newIndex);
            
            $currentItem.find('input[name*="payment_methods_items"]').each(function() {
                var oldName = $(this).attr('name');
                var newName = oldName.replace(/payment_methods_items\[\d+\]/, 'payment_methods_items[' + newIndex + ']');
                $(this).attr('name', newName);
            });
            $currentItem.find('.payment-method-image').attr('data-payment-method-index', newIndex);
        });
    });

    $(document).on('click', '.payment-method-image', function() {
        var $this = $(this);
        var paymentMethodIndex = $this.data('payment-method-index');
        var imageType = $this.data('image-type');

        var mediaPicker = new MediaPicker({
            multiple: false,
            type: 'image'
        });

        mediaPicker.on('select', function(file) {
            
            if (file && file.path && file.id) {
                $this.html(`
                    <img src="${file.path}" alt="payment-method-logo">
                    <input type="hidden" name="payment_methods_items[${paymentMethodIndex}][file_id]" value="${file.id}">
                `).addClass('has-image');
            } else {
                console.error('Invalid file data:', file);
                alert('Resim seçimi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
    });

    $('form').on('submit', function() {
        $('input[name*="payment_methods_items"]').each(function() {
            var $input = $(this);
            var inputName = $input.attr('name');
            var indexMatch = inputName.match(/payment_methods_items\[(\d+)\]/);
            
            if (indexMatch) {
                var index = parseInt(indexMatch[1]);
                var $item = $('.payment-method-item[data-index="' + index + '"]');
                
                if ($item.length === 0) {
                    $input.remove();
                }
            }
        });
        
        $('.payment-method-item').each(function() {
            var $item = $(this);
            var nameInput = $item.find('input[name*="[name]"]');
            var fileIdInput = $item.find('input[name*="[file_id]"]');
            
            if (!nameInput.val().trim() && !fileIdInput.val()) {
                $item.remove();
            }
        });
        
        if ($('.payment-method-item').length === 0) {
            $('<input type="hidden" name="payment_methods_items" value="">').appendTo($(this));
        }
    });
});
</script>
