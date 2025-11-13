@extends('storefront::public.account.layout')

@section('title', 'Ürün Düzenle')

@section('account_breadcrumb')
    <li><a href="{{ url('account/vendor/listings') }}">Ürünlerim</a></li>
    <li class="active">Ürün Düzenle</li>
@endsection

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Ürün Düzenle: {{ $listing->title }}</h4>
            <a href="{{ url('account/vendor/listings') }}" class="btn btn-secondary btn-sm">
                <i class="las la-arrow-left"></i> Geri Dön
            </a>
        </div>

        <div class="panel-body" style="padding: 30px;">
            <form action="{{ url('account/vendor/listings/' . $listing->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-9">
                        <!-- Başlık -->
                        <div class="form-group mb-3">
                            <label for="title">Ürün Başlığı <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $listing->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-group mb-3">
                            <label for="category_id">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Kategori Seçiniz</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="form-group mb-3">
                            <label for="description">Açıklama <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="8" 
                                      required>{{ old('description', $listing->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fiyat -->
                        <div class="form-group mb-3">
                            <label for="price">Fiyat <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $listing->price instanceof \Modules\Support\Money ? $listing->price->amount() : $listing->price) }}" 
                                   step="0.01" 
                                   min="0" 
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok Miktarı -->
                        <div class="form-group mb-3">
                            <label for="stock_qty">Stok Miktarı</label>
                            <input type="number" 
                                   class="form-control @error('stock_qty') is-invalid @enderror" 
                                   id="stock_qty" 
                                   name="stock_qty" 
                                   value="{{ old('stock_qty', $listing->stock_qty ?? 0) }}" 
                                   min="0">
                            @error('stock_qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teslimat Tipi -->
                        <div class="form-group mb-3">
                            <label>Teslimat Tipi <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_type" id="delivery_automatic" value="automatic" {{ old('delivery_type', $listing->delivery_type) == 'automatic' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delivery_automatic">Otomatik Teslimat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_type" id="delivery_manual" value="manual" {{ old('delivery_type', $listing->delivery_type) == 'manual' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delivery_manual">Manuel Teslimat</label>
                                </div>
                            </div>
                            @error('delivery_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Otomatik Teslimat Stok Alanı -->
                        <div id="automatic_delivery_fields" style="display: {{ old('delivery_type', $listing->delivery_type) == 'automatic' ? 'block' : 'none' }};">
                            <div class="form-group mb-3">
                                <label for="stock_items">Stok Öğeleri <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('stock_items') is-invalid @enderror" 
                                          id="stock_items" 
                                          name="stock_items" 
                                          rows="8"
                                          placeholder="Her satıra bir öğe girin...&#10;Örnek:&#10;Lisans Anahtarı 1&#10;Lisans Anahtarı 2&#10;Lisans Anahtarı 3">{{ old('stock_items', $listing->stockItems->pluck('stock_data')->implode("\n")) }}</textarea>
                                <small class="form-text text-muted">
                                    Her satıra bir ürün bilgisi (lisans kodu, hesap bilgisi vb.) girin. 
                                    Satıldığında otomatik olarak müşteriye iletilecektir.
                                    <br>
                                    <strong>Mevcut Stok: {{ $listing->stockItems()->available()->count() }} adet</strong>
                                </small>
                                @error('stock_items')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Manuel Teslimat Notu -->
                        <div id="manual_delivery_fields" style="display: {{ old('delivery_type', $listing->delivery_type) == 'manual' ? 'block' : 'none' }};">
                            <div class="form-group mb-3">
                                <label for="manual_delivery_note">Manuel Teslimat Notu</label>
                                <textarea class="form-control @error('manual_delivery_note') is-invalid @enderror" 
                                          id="manual_delivery_note" 
                                          name="manual_delivery_note" 
                                          rows="3">{{ old('manual_delivery_note', $listing->manual_delivery_note) }}</textarea>
                                <small class="form-text text-muted">Alıcıya özel teslimat bilgileri veya talimatlar.</small>
                                @error('manual_delivery_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mevcut Görseller -->
                        @if($listing->images->isNotEmpty())
                            <div class="form-group mb-3">
                                <label>Mevcut Görseller</label>
                                <div class="row">
                                    @foreach($listing->images as $image)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ $image->path }}" alt="{{ $listing->title }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Görseller -->
                        <div class="form-group mb-3">
                            <label for="images">Ürün Görselleri (Max 10)</label>
                            <input type="file" 
                                   class="form-control-file @error('images') is-invalid @enderror" 
                                   id="images" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*">
                            <small class="form-text text-muted">
                                JPG, PNG veya GIF formatında görsel yükleyebilirsiniz. Yeni görsel yüklerseniz eskiler silinecektir.
                            </small>
                            @error('images')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Görsel Önizleme -->
                            <div id="images-preview" class="mt-3" style="display: none;">
                                <label class="d-block mb-2"><strong>Yeni Seçilen Görseller:</strong></label>
                                <div id="preview-container" class="row"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>Durum</h5>
                            </div>
                            <div class="card-body">
                                @if($listing->status === 'draft')
                                    <span class="badge badge-secondary mb-2">Taslak</span>
                                    <p class="small text-muted">Bu ürün henüz yayında değil.</p>
                                @elseif($listing->status === 'pending')
                                    <span class="badge badge-warning mb-2">Onay Bekliyor</span>
                                    <p class="small text-muted">Admin onayı bekleniyor.</p>
                                @elseif($listing->status === 'approved')
                                    <span class="badge badge-success mb-2">Yayında</span>
                                    <p class="small text-muted">Ürününüz yayında.</p>
                                @elseif($listing->status === 'rejected')
                                    <span class="badge badge-danger mb-2">Reddedildi</span>
                                    <p class="small text-muted">Düzenleyip tekrar gönderebilirsiniz.</p>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>İşlemler</h5>
                            </div>
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary btn-block mb-2">
                                    <i class="las la-save"></i> Değişiklikleri Kaydet
                                </button>

                                @if($listing->status === 'draft')
                                    <form action="{{ url('account/vendor/listings/' . $listing->id . '/submit') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <i class="las la-paper-plane"></i> Onaya Gönder
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .panel-body {
            max-width: 100%;
        }
        .form-control {
            width: 100%;
            max-width: 100%;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            padding: 12px 16px;
        }
        .card-header h5 {
            margin: 0;
            font-size: 16px;
        }
        .card-body {
            padding: 20px;
        }
        textarea.form-control {
            min-height: 120px;
        }
        .row {
            margin-left: -15px;
            margin-right: -15px;
        }
        .col-md-9, .col-md-3, .col-md-6 {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>

    <script>
        // Teslimat tipi değiştiğinde ilgili alanları göster/gizle
        document.addEventListener('DOMContentLoaded', function() {
            const automaticRadio = document.getElementById('delivery_automatic');
            const manualRadio = document.getElementById('delivery_manual');
            const automaticFields = document.getElementById('automatic_delivery_fields');
            const manualFields = document.getElementById('manual_delivery_fields');

            function toggleDeliveryFields() {
                if (automaticRadio.checked) {
                    automaticFields.style.display = 'block';
                    manualFields.style.display = 'none';
                } else if (manualRadio.checked) {
                    automaticFields.style.display = 'none';
                    manualFields.style.display = 'block';
                }
            }

            automaticRadio.addEventListener('change', toggleDeliveryFields);
            manualRadio.addEventListener('change', toggleDeliveryFields);

            // Sayfa yüklendiğinde başlangıç durumunu ayarla
            toggleDeliveryFields();

            // Görsel Önizleme
            const imagesInput = document.getElementById('images');
            const imagesPreview = document.getElementById('images-preview');
            const previewContainer = document.getElementById('preview-container');

            if (imagesInput) {
                imagesInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    previewContainer.innerHTML = ''; // Önceki önizlemeleri temizle

                    if (files.length > 0) {
                        imagesPreview.style.display = 'block';

                        // Max 10 görsel kontrolü
                        if (files.length > 10) {
                            alert('Maksimum 10 görsel yükleyebilirsiniz!');
                            e.target.value = '';
                            imagesPreview.style.display = 'none';
                            return;
                        }

                        Array.from(files).forEach((file, index) => {
                            const reader = new FileReader();
                            
                            reader.onload = function(e) {
                                const col = document.createElement('div');
                                col.className = 'col-md-3 col-sm-4 col-xs-6 mb-3';
                                
                                col.innerHTML = `
                                    <div style="position: relative; border: 2px solid #ddd; border-radius: 8px; padding: 5px; background: #f8f9fa;">
                                        <img src="${e.target.result}" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                        <div style="text-align: center; padding: 5px; font-size: 12px;">
                                            ${index === 0 ? '<span class="badge badge-primary">Ana Görsel</span>' : `Görsel ${index + 1}`}
                                        </div>
                                    </div>
                                `;
                                
                                previewContainer.appendChild(col);
                            };
                            
                            reader.readAsDataURL(file);
                        });
                    } else {
                        imagesPreview.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection

