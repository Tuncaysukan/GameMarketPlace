<script>
    window.Veles = {
        version: '{{ veles_version() }}',
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/') }}',
        rtl: {{ is_rtl() ? 'true' : 'false' }},
        langs: {},
        data: {},
        errors: {},
        selectize: [],
        defaultCurrencySymbol: '{{ currency_symbol(setting("default_currency")) }}'
    };

    Veles.langs['admin::admin.buttons.delete'] = '{{ trans('admin::admin.buttons.delete') }}';
    Veles.langs['media::media.file_manager.title'] = '{{ trans('media::media.file_manager.title') }}';
    Veles.langs['admin::admin.table.search_here'] = '{{ trans('admin::admin.table.search_here') }}';
    Veles.langs['admin::admin.table.showing_start_end_total_entries'] = '{{ trans('admin::admin.table.showing_start_end_total_entries') }}';
    Veles.langs['admin::admin.table.showing_empty_entries'] = '{{ trans('admin::admin.table.showing_empty_entries') }}';
    Veles.langs['admin::admin.table.show_menu_entries'] = '{{ trans('admin::admin.table.show_menu_entries') }}';
    Veles.langs['admin::admin.table.filtered_from_max_total_entries'] = '{{ trans('admin::admin.table.filtered_from_max_total_entries') }}';
    Veles.langs['admin::admin.table.no_data_available_table'] = '{{ trans('admin::admin.table.no_data_available_table') }}';
    Veles.langs['admin::admin.table.loading'] = '{{ trans('admin::admin.table.loading') }}';
    Veles.langs['admin::admin.table.no_matching_records_found'] = '{{ trans('admin::admin.table.no_matching_records_found') }}';
</script>

@stack('globals')

@routes
