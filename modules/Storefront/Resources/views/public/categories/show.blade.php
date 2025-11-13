@extends('storefront::public.layout')

@section('title', $category->name)

@section('content')
    <section 
        x-data="CategoryShow({
            category: {{ json_encode($category) }},
            subCategories: {{ json_encode($subCategories) }}
        })"
        class="category-show-page product-search-wrap"
    >
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-section">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('categories.index') }}">{{ trans('storefront::categories.all_categories') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page" x-text="category.name"></li>
                    </ol>
                </nav>
            </div>
            
            <div class="product-search">
                <!-- Sol Taraf - Ana Kategori Bilgisi -->
                <div class="product-search-left">
                    <div class="category-info-card">
                        <div class="category-image-container">
                            <template x-if="categoryImage">
                                <img :src="categoryImage" :alt="category.name">
                            </template>
                            <template x-if="!categoryImage">
                                <img :src="placeholderImage" :alt="category.name">
                            </template>
                        </div>
                        <div class="content">
                            <h1 class="category-title" x-text="category.name"></h1>
                        </div>
                    </div>
                </div>
                
                <!-- Sağ Taraf - Alt Kategoriler -->
                <div class="product-search-right">
                    <template x-if="hasSubCategories">
                        <div class="subcategories-grid">
                            <template x-for="subCategory in subCategories" :key="subCategory.id">
                                <div class="subcategory-item">
                                    <div class="product-card category-card">
                                        <div class="product-card-top">
                                            <a :href="categoryUrl(subCategory)">
                                                <template x-if="hasSubCategoryImage(subCategory)">
                                                    <img :src="subCategoryImage(subCategory)" 
                                                         :alt="subCategory.name" 
                                                         loading="lazy">
                                                </template>
                                                <template x-if="!hasSubCategoryImage(subCategory)">
                                                    <img :src="placeholderImage" 
                                                         :alt="subCategory.name" 
                                                         loading="lazy">
                                                </template>
                                            </a>
                                        </div>
                                        
                                        <div class="product-card-middle">
                                            <a :href="categoryUrl(subCategory)" class="product-name">
                                                <h6 x-text="subCategory.name"></h6>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    
                    <template x-if="!hasSubCategories">
                        <!-- Eğer alt kategoriler yoksa boş mesajı -->
                        <div class="empty-categories-message">
                            <div class="empty-message">
                                <i class="las la-folder-open"></i>
                                <h3>{{ trans('storefront::categories.no_subcategories') }}</h3>
                                <p>{{ trans('storefront::categories.no_subcategories_description') }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/js/pages/categories/show/main.js',
    ])
@endpush
