<div class="row">
    <div class="col-md-8">
        {{ Form::text('name', trans('slider::attributes.name'), $errors, $slider, ['required' => true]) }}
        {{ Form::number('speed', trans('slider::attributes.speed'), $errors, $slider, ['placeholder' => trans('slider::sliders.form.300ms')]) }}
        {{ Form::checkbox('autoplay', trans('slider::attributes.autoplay'), trans('slider::sliders.form.enable_autoplay'), $errors, $slider, ['checked' => true]) }}

        <div class="autoplay-speed-field {{ ($slider->autoplay ?? true) ? '' : 'hide' }}">
            {{ Form::number('autoplay_speed', trans('slider::attributes.autoplay_speed'), $errors, $slider, ['placeholder' => trans('slider::sliders.form.3000ms'), 'checked' => true]) }}
        </div>

        {{ Form::checkbox('dots', trans('slider::attributes.dots'), trans('slider::sliders.form.show_dots'), $errors, $slider, ['checked' => true]) }}
        {{ Form::checkbox('arrows', trans('slider::attributes.arrows'), trans('slider::sliders.form.show_arrows'), $errors, $slider, ['checked' => true]) }}
    </div>
</div>

<div class="background-images-section">
    <div class="row">
        <div class="col-md-8">
            <h4>{{ trans('slider::sliders.slide.background_images') }}</h4>
            
            <div class="form-group">
                <label>{{ trans('slider::sliders.slide.desktop_background_image') }} <small class="text-muted">(1920x1080px)</small></label>
                <div class="slide-image" data-image-type="desktop-background">
                    @if ($slider->desktopBackgroundFile && $slider->desktopBackgroundFile->path)
                        <img src="{{ $slider->desktopBackgroundFile->path }}" alt="desktop-background">
                        <input type="hidden" name="desktop_background_file_id" value="{{ $slider->desktopBackgroundFile->id }}">
                        <button type="button" class="remove-image-btn" data-field-name="desktop_background_file_id">
                            <i class="fa fa-times"></i>
                        </button>
                    @else
                        <i class="fa fa-picture-o"></i>
                    @endif
                </div>
            </div>
            
            <div class="form-group">
                <label>{{ trans('slider::sliders.slide.mobile_background_image') }} <small class="text-muted">(768x1024px)</small></label>
                <div class="slide-image slide-image-small" data-image-type="mobile-background">
                    @if ($slider->mobileBackgroundFile && $slider->mobileBackgroundFile->path)
                        <img src="{{ $slider->mobileBackgroundFile->path }}" alt="mobile-background">
                        <input type="hidden" name="mobile_background_file_id" value="{{ $slider->mobileBackgroundFile->id }}">
                        <button type="button" class="remove-image-btn" data-field-name="mobile_background_file_id">
                            <i class="fa fa-times"></i>
                        </button>
                    @else
                        <i class="fa fa-mobile"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Settings tab'ındaki resim kaldırma butonları için
    $('.background-images-section .remove-image-btn').on('click', function(e) {
        e.stopPropagation();
        let fieldName = $(this).data('field-name');
        let imageContainer = $(this).closest('.slide-image');
        let imageType = imageContainer.data('image-type');
        
        // Resim tipine göre varsayılan icon'u belirle
        let defaultIcon = 'fa-picture-o';
        if (imageType === 'mobile-background') {
            defaultIcon = 'fa-mobile';
        }
        
        let html = `<i class="fa ${defaultIcon}"></i>`;
        imageContainer.html(html);
        
        // Hidden input'u boş değerle güncelle
        let existingInput = $(`input[name="${fieldName}"]`);
        if (existingInput.length > 0) {
            existingInput.val('');
        } else {
            // Eğer input yoksa oluştur
            imageContainer.append(`<input type="hidden" name="${fieldName}" value="">`);
        }
    });
});
</script>
