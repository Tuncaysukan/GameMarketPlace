@extends('storefront::public.layout')

@section('title', $listing->title)

@push('meta')
    <meta name="description" content="{{ $listing->meta_description ?? strip_tags(Str::limit($listing->description, 160)) }}">
    @if($listing->meta_keywords)
        <meta name="keywords" content="{{ $listing->meta_keywords }}">
    @endif
@endpush

@section('content')
    <div class="container" style="margin-top: 30px;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}">Ana Sayfa</a></li>
            <li><a href="{{ route('listings.index') }}">İlanlar</a></li>
            @if($listing->category)
                <li><a href="{{ route('listings.index', ['category' => $listing->category_id]) }}">
                    {{ $listing->category->name }}
                </a></li>
            @endif
            <li class="active">{{ $listing->title }}</li>
        </ol>

        <div class="row">
            <!-- Sol Kolon - Görsel ve Bilgiler -->
            <div class="col-md-8">
                <!-- Görsel Galerisi -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="listing-gallery">
                            @if($listing->images->isNotEmpty())
                                <div class="main-image" style="margin-bottom: 15px;">
                                    <img src="{{ $listing->primary_image->path }}" 
                                         alt="{{ $listing->title }}" 
                                         class="img-responsive"
                                         style="width: 100%; height: 500px; object-fit: contain; background: #f5f5f5;">
                                </div>
                                
                                @if($listing->images->count() > 1)
                                    <div class="thumbnail-images">
                                        <div class="row">
                                            @foreach($listing->images as $image)
                                                <div class="col-xs-3">
                                                    <img src="{{ $image->file->path }}" 
                                                         alt="{{ $listing->title }}"
                                                         class="img-thumbnail thumbnail-clickable"
                                                         style="width: 100%; height: 100px; object-fit: cover; cursor: pointer;"
                                                         onclick="changeMainImage('{{ $image->file->path }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @else
                                <img src="/images/placeholder.png" alt="{{ $listing->title }}" class="img-responsive">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- İlan Açıklaması -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-info-circle"></i> İlan Açıklaması</h3>
                    </div>
                    <div class="panel-body">
                        <div style="white-space: pre-wrap; line-height: 1.6;">{{ $listing->description }}</div>
                    </div>
                </div>

                <!-- Teslimat Bilgileri -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-truck"></i> Teslimat Bilgileri</h3>
                    </div>
                    <div class="panel-body">
                        @if($listing->isAutomaticDelivery())
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i>
                                <strong>Otomatik Teslimat:</strong> 
                                Ödeme onaylandıktan sonra ürün bilgileri otomatik olarak tarafınıza iletilecektir.
                            </div>
                            <p><strong>Stok Durumu:</strong> 
                                @if($listing->in_stock)
                                    <span class="label label-success">Stokta Var ({{ $listing->stock_qty }} adet)</span>
                                @else
                                    <span class="label label-danger">Stokta Yok</span>
                                @endif
                            </p>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>Manuel Teslimat:</strong> 
                                Sipariş verdikten sonra satıcı ile iletişime geçerek ürünü teslim alacaksınız.
                            </div>
                            @if($listing->manual_delivery_note)
                                <p><strong>Not:</strong> {{ $listing->manual_delivery_note }}</p>
                            @endif
                            <p><strong>Tahmini İşlem Süresi:</strong> {{ $listing->processing_days }} gün</p>
                        @endif
                    </div>
                </div>

                <!-- Benzer İlanlar -->
                @if($relatedListings->isNotEmpty())
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-th"></i> Benzer İlanlar</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                @foreach($relatedListings as $related)
                                    <div class="col-sm-3">
                                        <a href="{{ $related->url() }}" class="thumbnail">
                                            @if($related->primary_image)
                                                <img src="{{ $related->primary_image->path }}" 
                                                     alt="{{ $related->title }}"
                                                     style="height: 120px; object-fit: cover;">
                                            @else
                                                <img src="/images/placeholder.png" alt="{{ $related->title }}">
                                            @endif
                                            <div class="caption">
                                                <p style="font-size: 12px; margin: 5px 0;">{{ Str::limit($related->title, 30) }}</p>
                                                <p style="color: #28a745; font-weight: bold; margin: 0;">{{ $related->price->format() }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sağ Kolon - Satın Alma -->
            <div class="col-md-4">
                <!-- Fiyat ve Satın Alma -->
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="price-box" style="text-align: center; padding: 20px 0;">
                            <h2 style="color: #28a745; margin: 0; font-size: 36px; font-weight: bold;">
                                {{ $listing->price->format() }}
                            </h2>
                        </div>

                        @if($listing->is_available)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                
                                <div class="form-group">
                                    <label>Adet:</label>
                                    <input type="number" name="quantity" class="form-control" 
                                           value="1" min="1" 
                                           @if($listing->isAutomaticDelivery()) max="{{ $listing->stock_qty }}" @endif>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg btn-block">
                                    <i class="fa fa-shopping-cart"></i> Sepete Ekle
                                </button>
                            </form>

                            <button type="button" class="btn btn-warning btn-block" style="margin-top: 10px;">
                                <i class="fa fa-bolt"></i> Hemen Al
                            </button>
                        @else
                            <div class="alert alert-danger text-center">
                                <i class="fa fa-times-circle"></i>
                                <strong>Stokta Yok</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Satıcı Bilgileri -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-store"></i> Satıcı Bilgileri</h4>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" style="margin-bottom: 15px;">
                            @if($listing->vendor->logo)
                                <img src="{{ $listing->vendor->logo->path }}" 
                                     alt="{{ $listing->vendor->shop_name }}"
                                     class="img-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="img-circle bg-primary" 
                                     style="width: 80px; height: 80px; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                                    <i class="fa fa-store"></i>
                                </div>
                            @endif
                        </div>

                        <h4 class="text-center" style="margin-top: 0;">
                            <a href="{{ $listing->vendor->url() }}">{{ $listing->vendor->shop_name }}</a>
                        </h4>

                        @if($listing->vendor->rating > 0)
                            <div class="text-center" style="margin-bottom: 15px;">
                                <div style="color: #f39c12; font-size: 18px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $listing->vendor->rating)
                                            <i class="fa fa-star"></i>
                                        @else
                                            <i class="fa fa-star-o"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    {{ number_format($listing->vendor->rating, 1) }} / 5.0 
                                    ({{ $listing->vendor->rating_count }} değerlendirme)
                                </small>
                            </div>
                        @endif

                        <table class="table table-condensed" style="margin: 0;">
                            <tbody>
                                <tr>
                                    <td><i class="fa fa-check-circle text-success"></i> Toplam Satış</td>
                                    <td class="text-right"><strong>{{ $listing->vendor->total_orders }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-list"></i> Aktif İlan</td>
                                    <td class="text-right"><strong>{{ $listing->vendor->listings()->active()->count() }}</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <a href="{{ $listing->vendor->url() }}" class="btn btn-default btn-block">
                            <i class="fa fa-store"></i> Mağazayı Ziyaret Et
                        </a>
                    </div>
                </div>

                <!-- İlan Bilgileri -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-info"></i> İlan Bilgileri</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-condensed" style="margin: 0;">
                            <tbody>
                                <tr>
                                    <td>İlan No:</td>
                                    <td class="text-right"><strong>#{{ $listing->id }}</strong></td>
                                </tr>
                                @if($listing->sku)
                                    <tr>
                                        <td>Stok Kodu:</td>
                                        <td class="text-right"><strong>{{ $listing->sku }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>Kategori:</td>
                                    <td class="text-right">{{ $listing->category->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Görüntülenme:</td>
                                    <td class="text-right">{{ $listing->view_count }}</td>
                                </tr>
                                <tr>
                                    <td>Yayın Tarihi:</td>
                                    <td class="text-right">{{ $listing->created_at->format('d.m.Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Satıcının Diğer İlanları -->
        @if($vendorListings->isNotEmpty())
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-store"></i> {{ $listing->vendor->shop_name }} Mağazasının Diğer İlanları
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($vendorListings as $vendorListing)
                            <div class="col-sm-3">
                                <a href="{{ $vendorListing->url() }}" class="thumbnail">
                                    @if($vendorListing->primary_image)
                                        <img src="{{ $vendorListing->primary_image->path }}" 
                                             alt="{{ $vendorListing->title }}"
                                             style="height: 150px; object-fit: cover;">
                                    @else
                                        <img src="/images/placeholder.png" alt="{{ $vendorListing->title }}">
                                    @endif
                                    <div class="caption">
                                        <p style="font-size: 13px; margin: 5px 0; height: 40px; overflow: hidden;">
                                            {{ $vendorListing->title }}
                                        </p>
                                        <p style="color: #28a745; font-weight: bold; margin: 0;">
                                            {{ $vendorListing->price->format() }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function changeMainImage(imagePath) {
        document.querySelector('.main-image img').src = imagePath;
    }
</script>
@endpush

