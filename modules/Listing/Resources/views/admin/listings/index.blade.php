@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('listing::listings.listings'))

    <li class="active">{{ trans('listing::listings.listings') }}</li>
@endcomponent

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Toplam İlan</span>
                    <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Onay Bekleyen</span>
                    <span class="info-box-number">{{ $stats['pending'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aktif İlanlar</span>
                    <span class="info-box-number">{{ $stats['active'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Vitrin İlanlar</span>
                    <span class="info-box-number">{{ $stats['featured'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="box-tools">
                <a href="{{ url('admin/listings/pending') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-clock-o"></i> Onay Bekleyenler ({{ $stats['pending'] ?? 0 }})
                </a>
            </div>
        </div>
        <div class="box-body index-table" id="listings-table">
            @component('admin::components.table')
                @slot('thead')
                    <tr>
                        <th>{{ trans('admin::admin.table.id') }}</th>
                        <th>{{ trans('listing::listings.title') }}</th>
                        <th>{{ trans('vendor::vendors.vendor') }}</th>
                        <th>{{ trans('listing::listings.category') }}</th>
                        <th>{{ trans('listing::listings.price') }}</th>
                        <th>{{ trans('listing::listings.status') }}</th>
                        <th>{{ trans('listing::listings.view_count') }}</th>
                        <th data-sort>{{ trans('admin::admin.table.created') }}</th>
                    </tr>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        DataTable.setRoutes('#listings-table .table', {
            index: '{{ url("admin/listings") }}',
            show: '{{ url("admin/listings/:id:") }}',
        });

        new DataTable('#listings-table .table', {
            columns: [
                { data: 'id', width: '5%' },
                { data: 'title' },
                { data: 'vendor', orderable: false },
                { data: 'category', orderable: false },
                { data: 'price' },
                { data: 'status' },
                { data: 'view_count' },
                { data: 'created', name: 'created_at' },
            ],
        });
    </script>
@endpush

