@extends('storefront::public.account.layout')

@section('title', 'Referans Sistemi')

@section('account_breadcrumb')
    <li class="active">Referans Sistemi</li>
@endsection

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Referans Sistemi</h4>
        </div>
        <div class="panel-body">
            <div class="my-profile">
                <div style="background: #27548A; padding: 20px; border-radius: 5px; color: #fff; margin-bottom: 15px;">
                    <h5 class="text-white mb-2">Referans Sistemi</h5>
                    <ul>
                        <li>Referans bağlantınız ile kayıt olan kullanıcıların alışverişlerinden %{{ Auth::user()->reference_percentage }} bakiye kazanacaksınız.</li>
                        <li>Referans bağlantınız ile kayıt olan kullanıcıların tüm alışverişlerinden kazanırsınız.</li>
                        <li>Referans bağlantınız ile kayıt olan kullanıcıları aşağıda görüntüleyebilirsiniz.</li>
                        <li>Referans bağlantınız ile kayıt olan kullanıcıların alışverişlerinden kazandığınız bakiyeyi aşağıda görüntüleyebilirsiniz.</li>
                    </ul>
                </div>
                <form method="POST" action="{{ route('account.money-check.store') }}">
                    @csrf
                    <div class="row">
                        <div style="width: 100%;">
                            <div class="form-group">
                                <label for="reference">Referans Bağlantınız</label>
                                <input type="text" id="reference" class="form-control" value="{{ route('register', ['reference' => Auth::user()->id]) }}" disabled="" />
                            </div>
                            <button class="btn btn-primary" onclick="copyReference()">Kopyala</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h4>Kayıt Olan Kullanıcılar</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-borderless my-reviews-table">
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Email</th>
                            <th>Telefon Numarası</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($references_users as $references_user)
                        <tr>
                            @php $fullName = $references_user->first_name.$references_user->last_name; @endphp
                            <td>{{ \Illuminate\Support\Str::mask($fullName, '*', 1, strpos($fullName, ' ') - 1) }}</td>
                            <td>{{ \Illuminate\Support\Str::mask($references_user->email, '*', 2, strpos($references_user->email, '@') - 2) }}</td>
                            <td>{{ \Illuminate\Support\Str::mask($references_user->phone, '*', 1, strpos($references_user->phone, ' ') - 1) }}</td>
                            <td>{{ \Carbon\Carbon::parse($references_user->created_at)->translatedFormat('j F Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h4>Kazanç</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-borderless my-reviews-table">
                    <thead>
                        <tr>
                            <th>Üye</th>
                            <th>Sipariş Numarası</th>
                            <th>Kazanılan Bakiye</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($references_orders as $references_order)
                        <tr>
                            @php $fullName = $references_order->user_first_name.$references_order->user_last_name; @endphp
                            <td>{{ \Illuminate\Support\Str::mask($fullName, '*', 1, strpos($fullName, ' ') - 1) }}</td>
                            <td>{{ $references_order->order_id }}</td>
                            <td class="text-success fw-bold">+{{ number_format($references_order->amount, 2) }} TL</td>
                            <td>{{ \Carbon\Carbon::parse($references_order->created_at)->translatedFormat('j F Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
function copyReference() {
    var copyText = document.getElementById("reference");
    var tempInput = document.createElement("input");
    tempInput.value = copyText.value;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    alert("Referans bağlantınız kopyalandı!");
}
</script>

@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/profile/main.scss', 
    ])
@endpush
