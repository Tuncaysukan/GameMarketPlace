@extends('storefront::public.account.layout')

@section('title', 'Ürünlerim')

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Ürünlerim</h4>
            <a href="{{ url('account/vendor/listings/create') }}" class="btn btn-primary btn-sm">
                <i class="las la-plus"></i> Yeni Ürün Ekle
            </a>
        </div>

        <div class="panel-body">
            @if($listings->isEmpty())
                <div class="alert alert-info">
                    <i class="las la-info-circle"></i>
                    Henüz ürününüz bulunmamaktadır. <a href="{{ url('account/vendor/listings/create') }}">Yeni ürün eklemek için tıklayın.</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80">Görsel</th>
                                <th>Başlık</th>
                                <th>Kategori</th>
                                <th>Fiyat</th>
                                <th>Stok</th>
                                <th>Durum</th>
                                <th>Görüntülenme</th>
                                <th width="150">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listings as $listing)
                                <tr>
                                    <td>
                                        @if($listing->primary_image && $listing->primary_image->path)
                                            <img src="{{ $listing->primary_image->path }}" 
                                                 alt="{{ $listing->title }}" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="las la-image" style="font-size: 24px; color: #ccc;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $listing->title }}</strong>
                                        <br>
                                        <small class="text-muted">ID: #{{ $listing->id }}</small>
                                    </td>
                                    <td>
                                        @if($listing->category)
                                            {{ $listing->category->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ format_price($listing->price) }}</strong>
                                    </td>
                                    <td>
                                        @if($listing->in_stock)
                                            <span class="badge badge-success">{{ $listing->stock_qty }}</span>
                                        @else
                                            <span class="badge badge-danger">Stokta Yok</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($listing->status === 'draft')
                                            <span class="badge badge-secondary">Taslak</span>
                                        @elseif($listing->status === 'pending')
                                            <span class="badge badge-warning">Onay Bekliyor</span>
                                        @elseif($listing->status === 'approved')
                                            <span class="badge badge-success">Yayında</span>
                                        @elseif($listing->status === 'rejected')
                                            <span class="badge badge-danger">Reddedildi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="las la-eye"></i> {{ $listing->view_count ?? 0 }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ url('account/vendor/listings/' . $listing->id . '/edit') }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Düzenle">
                                                <i class="las la-edit"></i>
                                            </a>

                                            @if($listing->status === 'draft')
                                                <form action="{{ url('account/vendor/listings/' . $listing->id . '/submit') }}" 
                                                      method="POST" 
                                                      style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-success" 
                                                            title="Onaya Gönder">
                                                        <i class="las la-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ url('account/vendor/listings/' . $listing->id) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Bu ürünü silmek istediğinizden emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Sil">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .btn-group {
            display: flex;
            gap: 5px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        .badge-secondary { background: #6c757d; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
    </style>
@endsection

