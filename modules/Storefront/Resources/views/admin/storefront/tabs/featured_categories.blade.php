<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('storefront_featured_categories_section_enabled', trans('storefront::attributes.section_status'), trans('storefront::storefront.form.enable_featured_categories_section'), $errors, $settings) }}
        {{ Form::text('translatable[storefront_featured_categories_section_title]', trans('storefront::attributes.section_title'), $errors, $settings) }}
        {{ Form::text('translatable[storefront_featured_categories_section_subtitle]', trans('storefront::attributes.section_subtitle'), $errors, $settings) }}

        @for ($i = 1; $i <= 25; $i++)
            <div class="box-content clearfix">
                <h4 class="section-title">{{ trans('storefront::storefront.form.category_' . $i) }}</h4>

                {{ Form::select('storefront_featured_categories_section_category_' . $i . '_category_id', trans('storefront::attributes.category'), $errors, $categories, $settings) }}

                @include('storefront::admin.storefront.tabs.partials.products', [
                    'fieldNamePrefix' => 'storefront_featured_categories_section_category_' . $i,
                    'products' => $categoryProducts[$i] ?? collect(),
                    'featuredCategories' => true,
                ])
            </div>
        @endfor
    </div>
</div>
