<script type="text/html" id="slide-template">
    <div class="slide">
        <div class="slide-header clearfix">
            <span class="slide-drag pull-left">
                <i class="fa">&#xf142;</i>
                <i class="fa">&#xf142;</i>
            </span>

            <span class="pull-left">
                {{ trans('slider::sliders.slide.image_slide') }}
            </span>

            <button type="button" class="delete-slide btn pull-right">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="slide-body">
            <input type="hidden" name="slides[<%- slideNumber %>][id]" value="<%- slide.id %>">

            <div class="slide-images-section">
                <div class="main-slide-image">
                    <label>{{ trans('slider::sliders.slide.desktop_image') }} <small class="text-muted">(1400x500px)</small></label>
                    <div class="slide-image" data-slide-number="<%- slideNumber %>" data-image-type="desktop">
                        <% if (slide.file && slide.file.path) { %>
                            <img src="<%- slide.file.path %>" alt="desktop-image">
                            <input type="hidden" name="slides[<%- slideNumber %>][file_id]" value="<%- slide.file.id %>">
                            <button type="button" class="remove-image-btn" data-field-name="slides[<%- slideNumber %>][file_id]">
                                <i class="fa fa-times"></i>
                            </button>
                        <% } else { %>
                            <i class="fa fa-picture-o"></i>
                        <% } %>
                    </div>
                </div>
                
                <div class="additional-images">
                    <div class="mobile-image">
                        <label>{{ trans('slider::sliders.slide.mobile_image') }} <small class="text-muted">(920x570px)</small></label>
                        <div class="slide-image slide-image-small" data-slide-number="<%- slideNumber %>" data-image-type="mobile">
                            <% if (slide.mobile_file && slide.mobile_file.path) { %>
                                <img src="<%- slide.mobile_file.path %>" alt="mobile-image">
                                <input type="hidden" name="slides[<%- slideNumber %>][mobile_file_id]" value="<%- slide.mobile_file.id %>">
                                <button type="button" class="remove-image-btn" data-field-name="slides[<%- slideNumber %>][mobile_file_id]">
                                    <i class="fa fa-times"></i>
                                </button>
                            <% } else { %>
                                <i class="fa fa-mobile"></i>
                            <% } %>
                        </div>
                    </div>
                    
                    <div class="logo-image">
                        <label>{{ trans('slider::sliders.slide.logo_image') }} <small class="text-muted">(100x60px)</small></label>
                        <div class="slide-image slide-image-small" data-slide-number="<%- slideNumber %>" data-image-type="logo">
                            <% if (slide.logo_file && slide.logo_file.path) { %>
                                <img src="<%- slide.logo_file.path %>" alt="logo-image">
                                <input type="hidden" name="slides[<%- slideNumber %>][logo_file_id]" value="<%- slide.logo_file.id %>">
                                <button type="button" class="remove-image-btn" data-field-name="slides[<%- slideNumber %>][logo_file_id]">
                                    <i class="fa fa-times"></i>
                                </button>
                            <% } else { %>
                                <i class="fa fa-star"></i>
                            <% } %>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="slides-<%- slideNumber %>-caption-1">
                                {{ trans('slider::attributes.caption_1') }}
                            </label>

                            <input type="text"
                                name="slides[<%- slideNumber %>][caption_1]"
                                class="form-control"
                                id="slides-<%- slideNumber %>-caption-1"
                                value="<%- slide.caption_1 %>"
                            >
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="slides-<%- slideNumber %>-caption-2">
                                {{ trans('slider::attributes.caption_2') }}
                            </label>

                            <input type="text"
                                name="slides[<%- slideNumber %>][caption_2]"
                                class="form-control"
                                id="slides-<%- slideNumber %>-caption-2"
                                value="<%- slide.caption_2 %>"
                            >
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="slides-<%- slideNumber %>-direction">
                                {{ trans('slider::attributes.direction') }}
                            </label>

                            <select
                                name="slides[<%- slideNumber %>][direction]"
                                class="form-control custom-select-black"
                                id="slides-<%- slideNumber %>-direction"
                                value="<%- slide.direction %>"
                            >
                                <option value="left" <%= slide.direction === 'left' ? 'selected' : '' %>>
                                    {{ trans('slider::sliders.slide.form.directions.left') }}
                                </option>

                                <option value="right" <%= slide.direction === 'right' ? 'selected' : '' %>>
                                    {{ trans('slider::sliders.slide.form.directions.right') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="slides-<%- slideNumber %>-call-to-action-url">
                                {{ trans('slider::attributes.call_to_action_url') }}
                            </label>

                            <input type="text"
                                name="slides[<%- slideNumber %>][call_to_action_url]"
                                class="form-control"
                                id="slides-<%- slideNumber %>-call-to-action-url"
                                value="<%- slide.call_to_action_url %>"
                            >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="slides-<%- slideNumber %>-bullet-color">
                                {{ trans('slider::sliders.slide.bullet_color') }}
                            </label>

                            <input type="color"
                                name="slides[<%- slideNumber %>][options][bullet_color]"
                                class="form-control"
                                id="slides-<%- slideNumber %>-bullet-color"
                                value="<%- (slide.options && slide.options.bullet_color) ? slide.options.bullet_color : '#007bff' %>"
                            >
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-12">
                        <div class="checkbox">
                            <input type="hidden" name="slides[<%- slideNumber %>][open_in_new_window]" value="0">

                            <input type="checkbox"
                                name="slides[<%- slideNumber %>][open_in_new_window]"
                                value="1"
                                id="slides-<%- slideNumber %>-open-in-new-window"
                                <%= slide.open_in_new_window ? 'checked' : '' %>
                            >

                            <label for="slides-<%- slideNumber %>-open-in-new-window">
                                {{ trans('slider::attributes.open_in_new_window') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
