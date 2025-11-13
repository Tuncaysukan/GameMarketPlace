<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('shopier_enabled', trans('setting::attributes.shopier_enabled'), "Shopier'ı Aktif Et", $errors, $settings) }}
        {{ Form::text('translatable[shopier_label]', trans('setting::attributes.shopier_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[shopier_description]', trans('setting::attributes.shopier_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}

        <div class="{{ old('shopier_enabled', array_get($settings, 'shopier_enabled')) ? '' : 'hide' }}" id="shopier-fields">
            {{ Form::text('shopier_username', trans('setting::attributes.shopier_username'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('shopier_password', trans('setting::attributes.shopier_password'), $errors, $settings, ['required' => true]) }}

        </div>
    </div>
</div>

<script>
    $("#shopier_enabled").on("change", () => {
    $("#shopier-fields").toggleClass("hide");
});
    </script>