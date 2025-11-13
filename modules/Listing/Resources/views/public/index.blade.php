@extends('storefront::public.layout')

@section('title', 'İlanlar')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sol Sidebar - Filtreler -->
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-filter"></i> Filtreler</h4>
                    </div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('listings.index') }}">
                            <!-- Kategori -->
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tüm Kategoriler</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fiyat Aralığı -->
                            <div class="form-group">
                                <label>Fiyat Aralığı</label>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input type="number" name="min_price" class="form-control" 
                                               placeholder="Min" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="number" name="max_price" class="form-control" 
                                               placeholder="Max" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Arama -->
                            <div class="form-group">
                                <label>Arama</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="İlan ara..." value="{{ request('search') }}">
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-search"></i> Filtrele
                            </button>
                            <a href="{{ route('listings.index') }}" class="btn btn-default btn-block">
                                <i class="fa fa-times"></i> Temizle
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sağ İçerik - İlanlar -->
            <div class="col-md-9">
                <!-- Sıralama ve Sonuç Sayısı -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="text-muted">
                                    <strong>{{ $listings->total() }}</strong> ilan bulundu
                                </p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <form method="GET" action="{{ route('listings.index') }}" class="form-inline">
                                    @foreach(request()->except('sort') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <div class="form-group">
                                        <label>Sıralama:</label>
                                        <select name="sort" class="form-control input-sm" onchange="this.form.submit()">
                                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                                En Yeni
                                            </option>
                                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                                Fiyat (Düşük-Yüksek)
                                            </option>
                                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                                Fiyat (Yüksek-Düşük)
                                            </option>
                                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                                En Popüler
                                            </option>
                                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                                En Yüksek Puan
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- İlan Listesi -->
                @if($listings->isEmpty())
                    <div class="alert alert-info text-center" style="padding: 50px;">
                        <i class="fa fa-inbox fa-3x"></i>
                        <h4>İlan Bulunamadı</h4>
                        <p>Arama kriterlerinize uygun ilan bulunamadı.</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($listings as $listing)
                            <div class="col-sm-6 col-md-4">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <a href="{{ $listing->url() }}">
                                            @if($listing->primary_image)
                                                <img src="{{ $listing->primary_image->path }}" 
                                                     alt="{{ $listing->title }}"
                                                     class="img-responsive">
                                            @else
                                                <img src="/images/placeholder.png" 
                                                     alt="{{ $listing->title }}"
                                                     class="img-responsive">
                                            @endif
                                        </a>

                                        @if($listing->isFeatured())
                                            <span class="badge badge-warning" 
                                                  style="position: absolute; top: 10px; right: 10px;">
                                                <i class="fa fa-star"></i> Vitrin
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-card-body">
                                        <h4 class="product-card-title">
                                            <a href="{{ $listing->url() }}">{{ $listing->title }}</a>
                                        </h4>

                                        <div class="product-card-meta">
                                            <small class="text-muted">
                                                <i class="fa fa-store"></i>
                                                <a href="{{ $listing->vendor->url() }}">
                                                    {{ $listing->vendor->shop_name }}
                                                </a>
                                            </small>
                                            @if($listing->rating > 0)
                                                <br>
                                                <small>
                                                    <i class="fa fa-star text-warning"></i>
                                                    {{ number_format($listing->rating, 1) }}
                                                    ({{ $listing->rating_count }})
                                                </small>
                                            @endif
                                        </div>

                                        <div class="product-card-price">
                                            <span class="price">{{ $listing->price->format() }}</span>
                                        </div>

                                        <div class="product-card-actions">
                                            <a href="{{ $listing->url() }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i> İncele
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sayfalama -->
                    <div class="text-center" style="margin-top: 30px;">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .product-card {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
        transition: all 0.3s;
        height: 100%;
    }
    .product-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    .product-card-img {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f5f5f5;
    }
    .product-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-card-body {
        padding: 15px;
    }
    .product-card-title {
        font-size: 16px;
        margin: 0 0 10px;
        height: 40px;
        overflow: hidden;
    }
    .product-card-title a {
        color: #333;
        text-decoration: none;
    }
    .product-card-title a:hover {
        color: #007bff;
    }
    .product-card-price {
        margin: 15px 0;
    }
    .product-card-price .price {
        font-size: 24px;
        font-weight: bold;
        color: #28a745;
    }
</style>
@endpush

