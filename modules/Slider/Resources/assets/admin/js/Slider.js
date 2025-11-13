import Slide from './Slide';

export default class {
    constructor() {
        this.slideCount = 0;

        this.addSlides(Veles.data['slider.slides']);

        if (this.slideCount === 0) {
            this.addSlide();
        }

        this.attachEventListeners();
        this.makeSlidesSortable();
    }

    addSlides(slides) {
        for (let attributes of slides) {
            this.addSlide(attributes);
        }
    }

    addSlide(attributes = {}) {
        let slide = new Slide({ slideNumber: this.slideCount++, slide: attributes });

        $('#slides-wrapper').append(slide.render());
    }

    attachEventListeners() {
        $('.add-slide').on('click', () => {
            this.addSlide();
        });

        this.attachImagePickerEventListener();
    }

    attachImagePickerEventListener() {
        $('#slides-wrapper').on('click', '.slide-image', (e) => {
            let picker = new MediaPicker({ type: 'image' });
            let imageType = e.currentTarget.dataset.imageType || 'desktop';
            let fieldName = 'file_id';

            // Resim tipine göre field name'i belirle
            switch (imageType) {
                case 'mobile':
                    fieldName = 'mobile_file_id';
                    break;
                case 'logo':
                    fieldName = 'logo_file_id';
                    break;
                default:
                    fieldName = 'file_id';
            }

            picker.on('select', (file) => {
                let html = `
                    <img src="${file.path}">
                    <input type="hidden" name="slides[${e.currentTarget.dataset.slideNumber}][${fieldName}]" value="${file.id}">
                    <button type="button" class="remove-image-btn" data-field-name="${fieldName}">
                        <i class="fa fa-times"></i>
                    </button>
                `;

                $(e.currentTarget).html(html);
            });
        });

        // Background image picker için event listener
        $('.slide-image[data-image-type="desktop-background"], .slide-image[data-image-type="mobile-background"]').on('click', (e) => {
            let picker = new MediaPicker({ type: 'image' });
            let imageType = e.currentTarget.dataset.imageType;
            let fieldName = '';

            // Background image tipine göre field name'i belirle
            switch (imageType) {
                case 'desktop-background':
                    fieldName = 'desktop_background_file_id';
                    break;
                case 'mobile-background':
                    fieldName = 'mobile_background_file_id';
                    break;
            }

            picker.on('select', (file) => {
                let html = `
                    <img src="${file.path}">
                    <input type="hidden" name="${fieldName}" value="${file.id}">
                    <button type="button" class="remove-image-btn" data-field-name="${fieldName}">
                        <i class="fa fa-times"></i>
                    </button>
                `;

                $(e.currentTarget).html(html);
            });
        });

        // Resim kaldırma butonu için event listener
        $(document).on('click', '.remove-image-btn', function (e) {
            e.stopPropagation();
            let fieldName = $(this).data('field-name');
            let imageContainer = $(this).closest('.slide-image');
            let imageType = imageContainer.data('image-type');

            // Resim tipine göre varsayılan icon'u belirle
            let defaultIcon = 'fa-picture-o';
            if (imageType === 'mobile' || imageType === 'mobile-background') {
                defaultIcon = 'fa-mobile';
            } else if (imageType === 'logo') {
                defaultIcon = 'fa-star';
            }

            let html = `<i class="fa ${defaultIcon}"></i>`;
            imageContainer.html(html);

            // Hidden input'u boş değerle güncelle (temizleme)
            let existingInput = $(`input[name="${fieldName}"]`);
            if (existingInput.length > 0) {
                existingInput.val('');
            } else {
                // Eğer input yoksa oluştur
                imageContainer.append(`<input type="hidden" name="${fieldName}" value="">`);
            }
        });
    }

    makeSlidesSortable() {
        Sortable.create(document.getElementById('slides-wrapper'), {
            handle: '.slide-drag',
            animation: 150,
        });
    }
}
