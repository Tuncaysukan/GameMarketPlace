@extends('storefront::public.account.layout')

@section('title', 'Yeni Ürün Ekle')

@section('panel')
    <div class="panel">
        <div class="panel-header">
            <h4>Yeni Ürün Ekle</h4>
            <a href="{{ url('account/vendor/listings') }}" class="btn btn-secondary btn-sm">
                <i class="las la-arrow-left"></i> Geri Dön
            </a>
        </div>

        <div class="panel-body" style="padding: 30px;">
            <form action="{{ url('account/vendor/listings') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-10">
                        <!-- Başlık -->
                        <div class="form-group mb-3">
                            <label for="title">Ürün Başlığı <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-group mb-3">
                            <label for="category_id">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                <option value="">Kategori Seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <label for="description">Ürün Açıklaması <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="8"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ürününüzü detaylı bir şekilde açıklayın.</small>
                        </div>

                        <!-- Fiyat -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price">Fiyat (TL) <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock_qty">Stok Adedi</label>
                                    <input type="number"
                                           class="form-control @error('stock_qty') is-invalid @enderror"
                                           id="stock_qty"
                                           name="stock_qty"
                                           value="{{ old('stock_qty', 0) }}"
                                           min="0">
                                    @error('stock_qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Teslimat Tipi -->
                        <div class="form-group mb-3">
                            <label>Teslimat Tipi <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="delivery_type"
                                       id="delivery_automatic"
                                       value="automatic"
                                       {{ old('delivery_type', 'manual') == 'automatic' ? 'checked' : '' }}>
                                <label class="form-check-label" for="delivery_automatic">
                                    Otomatik Teslimat (Dijital Ürünler - Stok listesi)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="delivery_type"
                                       id="delivery_manual"
                                       value="manual"
                                       {{ old('delivery_type', 'manual') == 'manual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="delivery_manual">
                                    Manuel Teslimat (Fiziksel ürünler veya servisler)
                                </label>
                            </div>
                        </div>

                        <!-- Otomatik Teslimat - Stok Listesi -->
                        <div id="automatic_delivery_info" class="form-group mb-3" style="display: none;">
                            <label for="stock_items">Stok Öğeleri <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('stock_items') is-invalid @enderror"
                                      id="stock_items"
                                      name="stock_items"
                                      rows="8"
                                      placeholder="Her satıra bir öğe girin...&#10;Örnek:&#10;Lisans Anahtarı 1&#10;Lisans Anahtarı 2&#10;Lisans Anahtarı 3">{{ old('stock_items') }}</textarea>
                            <small class="form-text text-muted">
                                Her satıra bir ürün bilgisi (lisans kodu, hesap bilgisi vb.) girin. 
                                Satıldığında otomatik olarak müşteriye iletilecektir.
                            </small>
                            @error('stock_items')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Manuel Teslimat Bilgisi -->
                        <div id="manual_delivery_info" class="form-group mb-3">
                            <label for="delivery_note">Teslimat Notu</label>
                            <textarea class="form-control"
                                      id="delivery_note"
                                      name="delivery_note"
                                      rows="3">{{ old('delivery_note') }}</textarea>
                            <small class="form-text text-muted">Ürün teslimi ile ilgili önemli bilgiler.</small>
                        </div>

                        <!-- Ürün Görselleri -->
                        <div class="form-group mb-3">
                            <label>Ürün Görselleri (Maksimum 10 adet)</label>
                            <input type="file"
                                   id="images-input"
                                   class="form-control"
                                   name="images[]"
                                   multiple
                                   accept="image/*">
                            <small class="form-text text-muted">
                                JPG, PNG veya GIF formatında görsel yükleyebilirsiniz. İlk görsel ana görsel olarak kullanılacaktır.
                            </small>
                            
                            <!-- Görsel Önizleme -->
                            <div id="images-preview" class="mt-3" style="display: none;">
                                <label class="d-block mb-2"><strong>Seçilen Görseller:</strong></label>
                                <div id="preview-container" class="row"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h5>Yayınlama</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Durum</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                            Taslak (Henüz yayınlanmasın)
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Ürün kaydedildikten sonra admin onayına gönderebilirsiniz.
                                    </small>
                                </div>

                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="las la-save"></i> Ürünü Kaydet
                                    </button>
                                    <a href="{{ url('account/vendor/listings') }}" class="btn btn-secondary">
                                        <i class="las la-times"></i> İptal
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Yardım</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="las la-check text-success"></i> Net başlık yazın</li>
                                    <li><i class="las la-check text-success"></i> Detaylı açıklama ekleyin</li>
                                    <li><i class="las la-check text-success"></i> Kaliteli görseller kullanın</li>
                                    <li><i class="las la-check text-success"></i> Doğru kategori seçin</li>
                                </ul>
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
            const automaticInfo = document.getElementById('automatic_delivery_info');
            const manualInfo = document.getElementById('manual_delivery_info');

            function toggleDeliveryInfo() {
                if (automaticRadio && automaticRadio.checked) {
                    automaticInfo.style.display = 'block';
                    manualInfo.style.display = 'none';
                } else if (manualRadio && manualRadio.checked) {
                    automaticInfo.style.display = 'none';
                    manualInfo.style.display = 'block';
                }
            }

            if (automaticRadio) automaticRadio.addEventListener('change', toggleDeliveryInfo);
            if (manualRadio) manualRadio.addEventListener('change', toggleDeliveryInfo);

            toggleDeliveryInfo(); // İlk yüklemede çalıştır

            // Görsel Önizleme
            const imagesInput = document.getElementById('images-input');
            const imagesPreview = document.getElementById('images-preview');
            const previewContainer = document.getElementById('preview-container');

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
        });
    </script>
@endsection

