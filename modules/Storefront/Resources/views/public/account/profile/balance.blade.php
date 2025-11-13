@extends('storefront::public.account.layout')

@section('title', trans('storefront::account.pages.my_profile'))

@section('account_breadcrumb')
    <li class="active">{{ trans('storefront::account.pages.my_profile') }}</li>
@endsection

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Bakiye Yükle</h4>
        </div>

        <div class="panel-body">
            <div class="my-profile">
                <form method="POST" action="{{ route('account.profile.balance') }}">
                    @csrf


                    <div class="row">

                    
                    <?php if (isset($_GET["status"])) { ?>
                    <div class="col-md-12">
                    
                    <?php if ($_GET["status"] == "0") { ?>
                    <div class="alert alert-danger" style="position: relative">Bakiye yüklenirken bir hata oluştu.</div>
                    <?php } else if ($_GET["status"] == "1") { ?>
                    <div class="alert alert-success" style="position: relative">Bakiye başarıyla yüklenmiştir.</div>

                    <?php } ?>

                    </div>
                    <?php } ?>
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">
                                    Yüklenecek Tutar<span>*</span>
                                </label>

                                <input type="text" name="amount" id="amount" class="form-control">

                                @error('amount')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone">
                                    Ödeme Yöntemi<span>*</span>
                                </label>

                                <?php
                                
                                $methods = \Modules\Payment\Facades\Gateway::all();

                                $methods = $methods->all();


                                
                                ?>

                                <select class="form-control" name="payment_methods">
                                    @foreach ($methods as $key => $method)
                                        @if ($key != "balance")
                                        <option value="{{ $key }}">{{ $method->label }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @error('payment_methods')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-save-changes" data-loading>
                        Bakiyeyi Yükle
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/profile/main.scss', 
    ])
@endpush
