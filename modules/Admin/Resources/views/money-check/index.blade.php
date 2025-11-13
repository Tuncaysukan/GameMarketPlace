@extends('admin::layout')

@section('title', 'Para Çekim Talepleri')

@section('content_header')
    <h3 class="pull-left">Para Çekim Talepleri</h3>
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-body index-table" id="coupons-table">
        <div class="table-responsive">
            <div id="DataTables_Table_0_wrapper" class="dt-container form-inline dt-bootstrap dt-empty-footer">
                <div class="row dt-layout-row dt-layout-table">
                    <div class="dt-layout-cell col-12 dt-layout-full col-sm-12">
                        <table class="table table-striped table-hover dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>Banka</th>
                                    <th>IBAN Numarası</th>
                                    <th>Tutar (TL)</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($withdrawal_requests as $withdrawal_request)
                                <tr>
                                    <td><a href="/admin/users/{{ $withdrawal_request->user_id }}/edit?tab=account">{{ $withdrawal_request->user_first_name }} {{ $withdrawal_request->user_last_name }}</a></td>
                                    <td>{{ $withdrawal_request->bank }}</td>
                                    <td>{{ $withdrawal_request->iban }}</td>
                                    <td>{{ number_format($withdrawal_request->amount, 2) }} TL
                                        @if($withdrawal_request->status == 0)
                                            @if($withdrawal_request->amount <= $withdrawal_request->user_balance)
                                            <b class="text-success">(Bakiye Yeterli)</b>
                                            @else
                                            <b class="text-danger">(Bakiye Yetersiz)</b>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal_request->status == 0)
                                        <span class="badge badge-primary">Beklemede</span>
                                        @endif
                                        @if($withdrawal_request->status == 1)
                                        <span class="badge badge-success">Onaylandı</span>
                                        @endif
                                        @if($withdrawal_request->status == 2)
                                        <span class="badge badge-danger">Onaylanmadı</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal_request->status == 0)
                                        <a href="{{ route('admin.money-check.status', [$withdrawal_request->id, 'status' => 1]) }}" class="btn btn-success">Onayla</a>
                                        <a href="{{ route('admin.money-check.status', [$withdrawal_request->id, 'status' => 2]) }}" class="btn btn-danger">Onaylama</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
