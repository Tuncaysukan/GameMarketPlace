<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('balance_enabled', trans('setting::attributes.balance_enabled'), "Bakiye ile ödeme'yi Aktif Et", $errors, $settings) }}
        {{ Form::text('translatable[balance_label]', trans('setting::attributes.balance_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[balance_description]', trans('setting::attributes.balance_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}

    </div>
</div>

<script>
    $("#balance_enabled").on("change", () => {
    $("#balance-fields").toggleClass("hide");
});
    </script>