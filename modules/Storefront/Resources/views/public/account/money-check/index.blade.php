@extends('storefront::public.account.layout')

@section('title', 'Para Çekimi')

@section('account_breadcrumb')
    <li class="active">Para Çekimi</li>
@endsection

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Para Çekimi</h4>
        </div>
        <div class="panel-body">
            <div class="my-profile">
                <form method="POST" action="{{ route('account.money-check.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-20 col-sm-9">
                            <div class="form-group">
                                <label for="email"> Alıcı Adı Soyadı<span>*</span> </label>
                                <input type="text" id="recipient_full_name" class="form-control" value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}" disabled="" required="" />
                            </div>
                        </div>
                        <div class="col-20 col-sm-9">
                            <div class="form-group">
                                <label for="email"> Banka<span>*</span> </label>
                                <select class="form-control" id="bank" name="bank" required="">
                                    <option value="Adabank">Adabank</option>
                                    <option value="Akbank">Akbank</option>
                                    <option value="Albaraka Türk Katılım Bankası">Albaraka Türk Katılım Bankası</option>
                                    <option value="Alternatif Bank">Alternatif Bank</option>
                                    <option value="Anadolubank">Anadolubank</option>
                                    <option value="Arap Türk Bankası">Arap Türk Bankası</option>
                                    <option value="Bank Asya">Bank Asya</option>
                                    <option value="Bank Mellat">Bank Mellat</option>
                                    <option value="Bank of Tokyo-Mitsubishi UFJ Turkey">Bank of Tokyo-Mitsubishi UFJ Turkey</option>
                                    <option value="Birleşik Fon Bankası">Birleşik Fon Bankası</option>
                                    <option value="Burgan Bank">Burgan Bank</option>
                                    <option value="Citibank">Citibank</option>
                                    <option value="Denizbank">Denizbank</option>
                                    <option value="Deutsche Bank">Deutsche Bank</option>
                                    <option value="Fibabanka">Fibabanka</option>
                                    <option value="Finans Bank">Finans Bank</option>
                                    <option value="Habib Bank Limited">Habib Bank Limited</option>
                                    <option value="HSBC">HSBC</option>
                                    <option value="ING Bank">ING Bank</option>
                                    <option value="Intesa Sanpaolo">Intesa Sanpaolo</option>
                                    <option value="JP Morgan Chase Bank">JP Morgan Chase Bank</option>
                                    <option value="Kuveyt Türk Katılım Bankası">Kuveyt Türk Katılım Bankası</option>
                                    <option value="Odeabank">Odeabank</option>
                                    <option value="Rabobank">Rabobank</option>
                                    <option value="Société Générale">Société Générale</option>
                                    <option value="T-Bank">T-Bank</option>
                                    <option value="Tekstilbank">Tekstilbank</option>
                                    <option value="The Royal Bank of Scotland">The Royal Bank of Scotland</option>
                                    <option value="Turkish Bank">Turkish Bank</option>
                                    <option value="Türk Ekonomi Bankası">Türk Ekonomi Bankası</option>
                                    <option value="Türkiye Cumhuriyeti Ziraat Bankası">Türkiye Cumhuriyeti Ziraat Bankası</option>
                                    <option value="Türkiye Finans Katılım Bankası">Türkiye Finans Katılım Bankası</option>
                                    <option value="Türkiye Garanti Bankası">Türkiye Garanti Bankası</option>
                                    <option value="Türkiye Halk Bankası">Türkiye Halk Bankası</option>
                                    <option value="Türkiye İş Bankası">Türkiye İş Bankası</option>
                                    <option value="Türkiye Vakıflar Bankası">Türkiye Vakıflar Bankası</option>
                                    <option value="Yapı ve Kredi Bankası">Yapı ve Kredi Bankası</option>
                                    <option value="Şekerbank">Şekerbank</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-20 col-sm-9">
                            <div class="form-group">
                                <label for="iban">IBAN Numarası<span>*</span></label>
                                <input type="text" id="iban" name="iban" class="form-control" required="" />
                            </div>
                        </div>
                        <div class="col-20 col-sm-9">
                            <div class="form-group">
                                <label for="email"> Tutar (TL)<span>*</span> </label>
                                <input type="number" id="amount" name="amount" class="form-control" max="{{ Auth::user()->balance }}" placeholder="" required="" />
                                <small class="text-secondary">En fazla {{ Auth::user()->balance }} TL kadar çekim isteği gönderebilirsiniz.</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary btn-save-changes" data-loading>
                        Talep Gönder
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h4>Para Çekimi Talepleriniz</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-borderless my-reviews-table">
                    <thead>
                        <tr>
                            <th>Alıcı Adı Soyadı</th>
                            <th>Banka</th>
                            <th>IBAN Numarası</th>
                            <th>Tutar (TL)</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($withdrawal_requests as $withdrawal_request)
                        <tr>
                            <td>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</td>
                            <td>{{ $withdrawal_request -> bank }}</td>
                            <td>{{ $withdrawal_request -> iban }}</td>
                            <td>{{ number_format($withdrawal_request->amount, 2) }} TL</td>
                            <td>
                                @if($withdrawal_request->status == 0)
                                <span class="badge badge-warning">Beklemede</span>
                                @endif

                                @if($withdrawal_request->status == 1)
                                <span class="badge badge-success">Onaylandı</span>
                                @endif

                                @if($withdrawal_request->status == 2)
                                <span class="badge badge-danger">Onaylanmadı</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($withdrawal_request->created_at)->translatedFormat('j F Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let ibanInput = document.getElementById("iban");

    ibanInput.value = "TR";

    ibanInput.addEventListener("input", function (event) {
        let value = ibanInput.value;

        if (!value.startsWith("TR")) {
            ibanInput.value = "TR";
        }

        let numbersOnly = value.substring(2).replace(/\D/g, "");

        numbersOnly = numbersOnly.substring(0, 24);

        ibanInput.value = "TR" + numbersOnly;
    });

    ibanInput.addEventListener("keydown", function (event) {
        if (ibanInput.selectionStart <= 2 && (event.key === "Backspace" || event.key === "Delete")) {
            event.preventDefault();
        }
    });
});
</script>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/profile/main.scss', 
    ])
@endpush
