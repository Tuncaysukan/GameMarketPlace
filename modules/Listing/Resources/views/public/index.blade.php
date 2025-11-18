@extends('storefront::public.layout')

@section('title', 'İlanlar')

@section('content')
    <div class="container listings-page">
        <!-- Sayfa Başlığı ve Sonuç/Sıralama -->
        <div class="panel panel-default listings-header">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="page-title">
                            <i class="fa fa-list-alt text-primary"></i>
                            İlanlar
                            <small class="results-count">
                                <strong class="text-primary">{{ $listings->total() }}</strong> ilan bulundu
                            </small>
                        </h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <form method="GET" action="{{ route('listings.index') }}" class="form-inline sort-form">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div class="form-group">
                                <label class="sort-label">
                                    <i class="fa fa-sort text-muted"></i>
                                    Sıralama:
                                </label>
                                <select name="sort" class="form-control sort-select" onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>En Yeni</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Fiyat (Düşük-Yüksek)</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Fiyat (Yüksek-Düşük)</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>En Popüler</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>En Yüksek Puan</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sol Sidebar - Filtreler -->
            <div class="col-md-3">
                <div class="panel panel-default filters-panel">
                    <div class="panel-heading filters-header">
                        <h4 class="panel-title">
                            <i class="fa fa-filter"></i> Filtreler
                        </h4>
                    </div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('listings.index') }}" id="filter-form">
                            <div class="form-group">
                                <label class="filter-label">
                                    <i class="fa fa-folder text-primary"></i>
                                    Kategori
                                </label>
                                <select name="category" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tüm Kategoriler</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="filter-label">
                                    <i class="fa fa-tag text-success"></i>
                                    Fiyat Aralığı
                                </label>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input type="number" name="min_price" class="form-control" placeholder="Min ₺" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="number" name="max_price" class="form-control" placeholder="Max ₺" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="filter-label">
                                    <i class="fa fa-search text-muted"></i>
                                    Arama
                                </label>
                                <input type="text" name="search" class="form-control" placeholder="İlan ara..." value="{{ request('search') }}">
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-search"></i> Filtrele
                                    </button>
                                </div>
                                <div class="col-xs-6">
                                    <a href="{{ route('listings.index') }}" class="btn btn-default btn-block">
                                        <i class="fa fa-times"></i> Temizle
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sağ İçerik - İlanlar -->
            <div class="col-md-9 listings-content">
                @if($listings->isEmpty())
                    <div class="panel panel-default empty-state">
                        <div class="panel-body text-center">
                            <i class="fa fa-inbox empty-icon"></i>
                            <h3 class="empty-title">İlan Bulunamadı</h3>
                            <p class="empty-text">Arama kriterlerinize uygun ilan bulunamadı.</p>
                        </div>
                    </div>
                @else
                    <div class="row listings-grid">
                        @foreach($listings as $listing)
                            <div class="col-xs-6 col-sm-4 col-md-3 product-col">
                                <div class="product-card">
                                    <div class="product-image-wrapper">
                                        <a href="{{ $listing->url() }}" class="product-image-link">
                                            @if($listing->primary_image && $listing->primary_image->path)
                                                <img src="{{ $listing->primary_image->path }}" alt="{{ $listing->title }}" class="product-image">
                                            @else
                                                <div class="product-placeholder">
                                                    <i class="fa fa-image"></i>
                                                </div>
                                            @endif
                                        </a>
                                        
                                        @if($listing->isFeatured())
                                            <span class="product-badge product-badge-featured">
                                                <i class="fa fa-star"></i> Vitrin
                                            </span>
                                        @endif

                                        @if($listing->in_stock)
                                            <span class="product-badge product-badge-stock">
                                                <i class="fa fa-check-circle"></i> Stokta
                                            </span>
                                        @endif
                                        
                                        <button class="product-wishlist" type="button">
                                            <i class="fa fa-heart-o"></i>
                                        </button>
                                    </div>

                                    <div class="product-body">
                                        <h5 class="product-title">
                                            <a href="{{ $listing->url() }}">{{ $listing->title }}</a>
                                        </h5>

                                        <div class="product-vendor">
                                            <i class="fa fa-store"></i>
                                            <a href="{{ $listing->vendor->url() }}">{{ $listing->vendor->shop_name }}</a>
                                        </div>
                                        
                                        @if($listing->rating > 0)
                                            <div class="product-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($listing->rating))
                                                        <i class="fa fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $listing->rating)
                                                        <i class="fa fa-star-half-o text-warning"></i>
                                                    @else
                                                        <i class="fa fa-star-o text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="rating-value">{{ number_format($listing->rating, 1) }}</span>
                                            </div>
                                        @endif

                                        <div class="product-price">
                                            <span class="price-current">{{ $listing->price->format() }}</span>
                                        </div>

                                        <a href="{{ $listing->url() }}" class="btn btn-primary btn-block product-btn">
                                            <i class="fa fa-shopping-cart"></i> İncele
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sayfalama -->
                    <div class="text-center pagination-wrapper">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
{!! file_get_contents(module_path('Listing') . '/Resources/assets/public/css/listings.css') !!}
</style>
@endpush
