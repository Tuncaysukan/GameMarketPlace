@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('listing::listings.listings'))

    <li><a href="{{ url('admin/listings') }}">{{ trans('listing::listings.listings') }}</a></li>
    <li class="active">{{ trans('listing::listings.pending') }}</li>
@endcomponent

@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-clock-o"></i> {{ trans('listing::listings.pending_listings') }}
            </h3>
            <div class="box-tools">
                <a href="{{ url('admin/listings') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-list"></i> {{ trans('listing::listings.all_listings') }}
                </a>
            </div>
        </div>
        <div class="box-body">
            @if($listings->isEmpty())
                <p class="text-muted text-center" style="padding: 50px 0;">
                    <i class="fa fa-inbox fa-3x"></i><br><br>
                    Onay bekleyen ilan bulunmamaktadır.
                </p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="80">#</th>
                            <th width="100">Görsel</th>
                            <th>İlan Başlığı</th>
                            <th>Satıcı</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th>Teslimat</th>
                            <th>Tarih</th>
                            <th width="200" class="text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listings as $listing)
                            <tr>
                                <td>{{ $listing->id }}</td>
                                <td>
                                    @if($listing->primary_image)
                                        <img src="{{ $listing->primary_image->path }}" alt="{{ $listing->title }}" 
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $listing->title }}</strong><br>
                                    <small class="text-muted">SKU: {{ $listing->sku ?? '-' }}</small>
                                </td>
                                <td>
                                    <a href="{{ url('admin/vendors/' . $listing->vendor_id) }}">
                                        {{ $listing->vendor->shop_name }}
                                    </a>
                                </td>
                                <td>{{ $listing->category->name ?? '-' }}</td>
                                <td><strong>{{ $listing->price->format() }}</strong></td>
                                <td>
                                    @if($listing->delivery_type === 'automatic')
                                        <span class="label label-success">Otomatik</span><br>
                                        <small>Stok: {{ $listing->stock_qty }}</small>
                                    @else
                                        <span class="label label-info">Manuel</span>
                                    @endif
                                </td>
                                <td>{{ $listing->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-right">
                                    <form action="{{ url('admin/listings/' . $listing->id . '/approve') }}" 
                                          method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                title="{{ trans('listing::listings.approve_listing') }}">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-toggle="modal" data-target="#rejectModal{{ $listing->id }}"
                                            title="{{ trans('listing::listings.reject_listing') }}">
                                        <i class="fa fa-times"></i>
                                    </button>

                                    <a href="{{ url('ilan/' . $listing->slug . '-' . $listing->id) }}" target="_blank" class="btn btn-default btn-sm"
                                       title="Ön İzleme">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $listing->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ url('admin/listings/' . $listing->id . '/reject') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">{{ trans('listing::listings.reject_listing') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>İlan:</strong> {{ $listing->title }}</p>
                                                <div class="form-group">
                                                    <label>{{ trans('listing::listings.rejection_reason') }}</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                                                <button type="submit" class="btn btn-danger">Reddet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    {{ $listings->links('admin::pagination.simple') }}
                </div>
            @endif
        </div>
    </div>
@endsection

