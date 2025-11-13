@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('category::categories.categories'))

    <li class="active">{{ trans('category::categories.categories') }}</li>
@endcomponent

@section('content')
    <div class="box box-default">
        <div class="box-body clearfix">
            <div class="col-lg-2 col-md-3">
                <div class="row">
                    <button class="btn btn-default add-root-category">{{ trans('category::categories.tree.add_root_category') }}</button>
                    <button class="btn btn-default add-sub-category disabled">{{ trans('category::categories.tree.add_sub_category') }}</button>

                    <div class="m-b-10">
                        <a href="#" class="collapse-all">{{ trans('category::categories.tree.collapse_all') }}</a> |
                        <a href="#" class="expand-all">{{ trans('category::categories.tree.expand_all') }}</a>
                    </div>

                    <div class="category-tree"></div>
                </div>
            </div>

            <div class="col-lg-10 col-md-9">
                <div class="tab-wrapper category-details-tab">
                    <ul class="nav nav-tabs">
                        <li class="general-information-tab active"><a data-toggle="tab" href="#general-information">{{ trans('category::categories.tabs.general') }}</a></li>

                        @hasAccess('admin.media.index')
                            <li class="image-tab"><a data-toggle="tab" href="#image">{{ trans('category::categories.tabs.image') }}</a></li>
                        @endHasAccess

                        <li class="styling-tab"><a data-toggle="tab" href="#styling">{{ trans('category::categories.tabs.styling') }}</a></li>
                        <li class="seo-tab hide"><a data-toggle="tab" href="#seo">{{ trans('category::categories.tabs.seo') }}</a></li>
                    </ul>

                    <form method="POST" action="{{ route('admin.categories.store') }}" class="form-horizontal" id="category-form" novalidate>
                        {{ csrf_field() }}

                        <div class="tab-content">
                            <div id="general-information" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="id-field" class="hide">
                                            {{ Form::text('id', trans('category::attributes.id'), $errors, null, ['disabled' => true]) }}
                                        </div>

                                        {{ Form::text('name', trans('category::attributes.name'), $errors, null, ['required' => true]) }}
                                        {{ Form::checkbox('is_searchable', trans('category::attributes.is_searchable'), trans('category::categories.form.show_this_category_in_search_box'), $errors) }}
                                        {{ Form::checkbox('is_active', trans('category::attributes.is_active'), trans('category::categories.form.enable_the_category'), $errors) }}
                                    </div>
                                </div>
                            </div>

                            @if (auth()->user()->hasAccess('admin.media.index'))
                                <div id="image" class="tab-pane fade">
                                    <div class="logo">
                                        @include('media::admin.image_picker.single', [
                                            'title' => trans('category::categories.form.logo'),
                                            'inputName' => 'files[logo]',
                                            'file' => (object) ['exists' => false],
                                        ])
                                    </div>

                                    <div class="banner">
                                        @include('media::admin.image_picker.single', [
                                            'title' => trans('category::categories.form.banner'),
                                            'inputName' => 'files[banner]',
                                            'file' => (object) ['exists' => false],
                                        ])
                                    </div>

                                    <div class="media-picker-divider"></div>

                                    <div class="category-image">
                                        @include('media::admin.image_picker.single', [
                                            'title' => trans('category::categories.form.category_image'),
                                            'inputName' => 'files[category_image]',
                                            'file' => (object) ['exists' => false],
                                        ])
                                        <div class="help-block">
                                            <small class="text-muted">{{ trans('category::categories.form.category_image_help') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div id="styling" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ trans('category::attributes.background_color') }}</label>
                                            <input type="color" name="background_color" value="{{ old('background_color', $category->background_color ?? '#ffffff') }}" class="form-control" />
                                            <span class="help-block">{{ trans('category::categories.form.background_color_help') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ trans('category::attributes.hover_background_color') }}</label>
                                            <input type="color" name="hover_background_color" value="{{ old('hover_background_color', $category->hover_background_color ?? '#f8f9fa') }}" class="form-control" />
                                            <span class="help-block">{{ trans('category::categories.form.hover_background_color_help') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ trans('category::attributes.text_color') }}</label>
                                            <input type="color" name="text_color" value="{{ old('text_color', $category->text_color ?? '#333333') }}" class="form-control" />
                                            <span class="help-block">{{ trans('category::categories.form.text_color_help') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">{{ trans('category::attributes.hover_text_color') }}</label>
                                            <input type="color" name="hover_text_color" value="{{ old('hover_text_color', $category->hover_text_color ?? '#ffffff') }}" class="form-control" />
                                            <span class="help-block">{{ trans('category::categories.form.hover_text_color_help') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="seo" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="hide" id="slug-field">
                                            {{ Form::text('slug', trans('category::attributes.slug'), $errors) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <button type="submit" class="btn btn-primary" data-loading>
                                    {{ trans('admin::admin.buttons.save') }}
                                </button>

                                <button type="button" class="btn btn-link text-red btn-delete p-l-0 hide" data-confirm>
                                    {{ trans('admin::admin.buttons.delete') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="overlay loader hide">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
@endsection

@push('globals')
    <script type="module" src="{{ v(asset('build/assets/jstree.min.js')) }}"></script>

    @vite([
        'modules/Category/Resources/assets/admin/sass/main.scss',
        'modules/Category/Resources/assets/admin/js/main.js',
        'modules/Media/Resources/assets/admin/sass/main.scss',
        'modules/Media/Resources/assets/admin/js/main.js',
    ])
@endpush
