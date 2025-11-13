<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label class="control-label">{{ trans('menu::attributes.background_color') }}</label>
            <input type="color" name="background_color" value="{{ old('background_color', $menuItem->background_color ?? '#ffffff') }}" class="form-control" />
            <span class="help-block">{{ trans('menu::menu_items.form.background_color_help') }}</span>
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('menu::attributes.hover_background_color') }}</label>
            <input type="color" name="hover_background_color" value="{{ old('hover_background_color', $menuItem->hover_background_color ?? '#f8f9fa') }}" class="form-control" />
            <span class="help-block">{{ trans('menu::menu_items.form.hover_background_color_help') }}</span>
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('menu::attributes.text_color') }}</label>
            <input type="color" name="text_color" value="{{ old('text_color', $menuItem->text_color ?? '#333333') }}" class="form-control" />
            <span class="help-block">{{ trans('menu::menu_items.form.text_color_help') }}</span>
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('menu::attributes.hover_text_color') }}</label>
            <input type="color" name="hover_text_color" value="{{ old('hover_text_color', $menuItem->hover_text_color ?? '#ffffff') }}" class="form-control" />
            <span class="help-block">{{ trans('menu::menu_items.form.hover_text_color_help') }}</span>
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('menu::attributes.after_color') }}</label>
            <input type="color" name="after_color" value="{{ old('after_color', $menuItem->after_color ?? '#000000') }}" class="form-control" />
            <span class="help-block">{{ trans('menu::menu_items.form.after_color_help') }}</span>
        </div>
    </div>
</div>
