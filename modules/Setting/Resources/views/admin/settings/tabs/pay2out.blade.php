<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('pay2out_enabled', trans('setting::attributes.paytr_enabled'), "Pay2Out'u Aktif Et", $errors, $settings) }}
        {{ Form::text('translatable[pay2out_label]', trans('setting::attributes.paytr_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[pay2outdescription]', trans('setting::attributes.paytr_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
        {{ Form::checkbox('pay2out_test_mode', trans('setting::attributes.paytr_test_mode'), 'Test modunu açarak test ödemesi yapabilirsiniz', $errors, $settings) }}

        <div class="{{ old('pay2out_enabled', array_get($settings, 'pay2out_enabled')) ? '' : 'hide' }}" id="pay2out-fields">
            {{ Form::text('pay2out_merchant_salt', trans('setting::attributes.paytr_merchant_salt'), $errors, $settings, ['required' => true]) }}

        </div>
    </div>
</div>

<script>
    $("#pay2out_enabled").on("change", () => {
    $("#pay2out-fields").toggleClass("hide");
});
    </script>
