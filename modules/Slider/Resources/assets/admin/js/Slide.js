export default class {
    constructor(data) {
        this.slidePanelHtml = this.getSlidePanelHtml(data);
    }

    getSlidePanelHtml(data) {
        data.slide.options = data.slide.options || this.getDefaultOptions();

        let template = _.template($('#slide-template').html());

        return $(template(data));
    }

    getDefaultOptions() {
        return { caption_1: {}, caption_2: {}, direction: 'left', call_to_action: {} };
    }

    render() {
        this.attachEventListeners();
        this.showSelectedOptionBlock();
        this.addRemoveButtonsToExistingImages();

        return this.slidePanelHtml;
    }

    attachEventListeners() {
        this.slidePanelHtml.find('.delete-slide').on('click', () => {
            this.slidePanelHtml.remove();
        });

        this.slidePanelHtml.find('.change-option-block').on('change', (e) => {
            this.slidePanelHtml.find('.slide-options').hide();
            this.slidePanelHtml.find(`.${e.currentTarget.value}`).show();
        });
    }

    showSelectedOptionBlock() {
        setTimeout(() => {
            this.slidePanelHtml.find('.change-option-block').trigger('change');
        });
    }

    addRemoveButtonsToExistingImages() {
        // Mevcut resimler için kaldırma butonu ekle
        this.slidePanelHtml.find('.slide-image img').each((index, element) => {
            let $imageContainer = $(element).closest('.slide-image');
            let imageType = $imageContainer.data('image-type');
            let slideNumber = $imageContainer.data('slide-number');
            let fieldName = '';

            // Resim tipine göre field name'i belirle
            switch (imageType) {
                case 'mobile':
                    fieldName = `slides[${slideNumber}][mobile_file_id]`;
                    break;
                case 'logo':
                    fieldName = `slides[${slideNumber}][logo_file_id]`;
                    break;
                default:
                    fieldName = `slides[${slideNumber}][file_id]`;
            }

            // Eğer kaldırma butonu yoksa ekle
            if ($imageContainer.find('.remove-image-btn').length === 0) {
                let removeButton = $(`
                    <button type="button" class="remove-image-btn" data-field-name="${fieldName}">
                        <i class="fa fa-times"></i>
                    </button>
                `);
                $imageContainer.append(removeButton);
            }
        });
    }
}
