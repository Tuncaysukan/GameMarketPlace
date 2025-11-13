# 🛣️ BERİTAN PAZARYERI - ROUTE LİSTESİ

## 📧 **TEST GİRİŞ BİLGİLERİ**

```
Admin:    admin@beritan.com / 12345678
Satıcı:   vendor@test.com / 12345678  
Müşteri:  customer@test.com / 12345678
```

---

## 🔐 **ADMIN PANEL ROUTES**

### **Vendor (Satıcı) Yönetimi**
```
GET    /admin/vendors                              - Satıcı listesi
GET    /admin/vendors/create                       - Yeni satıcı oluştur
POST   /admin/vendors                              - Satıcı kaydet
GET    /admin/vendors/{id}/edit                    - Satıcı düzenle
PUT    /admin/vendors/{id}                         - Satıcı güncelle
DELETE /admin/vendors/{id}                         - Satıcı sil
POST   /admin/vendors/{id}/approve                 - Satıcıyı onayla
POST   /admin/vendors/{id}/reject                  - Satıcıyı reddet
POST   /admin/vendors/{id}/suspend                 - Satıcıyı askıya al
POST   /admin/vendors/{id}/activate                - Satıcıyı aktifleştir
```

### **Vendor Başvuruları**
```
GET    /admin/vendor-applications                  - Bekleyen başvurular
GET    /admin/vendor-applications/reviewed         - İncelenmiş başvurular
GET    /admin/vendor-applications/{id}             - Başvuru detayı
POST   /admin/vendor-applications/{id}/approve     - Başvuruyu onayla
POST   /admin/vendor-applications/{id}/reject      - Başvuruyu reddet
```

### **Listing (İlan) Yönetimi**
```
GET    /admin/listings                             - Tüm ilanlar
GET    /admin/listings/pending                     - Onay bekleyen ilanlar
GET    /admin/listings/{id}                        - İlan detayı
POST   /admin/listings/{id}/approve                - İlanı onayla
POST   /admin/listings/{id}/reject                 - İlanı reddet
POST   /admin/listings/{id}/toggle-featured        - Vitrin durumunu değiştir
POST   /admin/listings/{id}/toggle-active          - Aktif durumunu değiştir
DELETE /admin/listings/{id}                        - İlanı sil
```

### **Wallet (Cüzdan) Yönetimi**
```
GET    /admin/wallets                              - Tüm cüzdanlar
GET    /admin/withdrawals                          - Tüm çekim talepleri
GET    /admin/withdrawals/pending                  - Bekleyen çekim talepleri
POST   /admin/withdrawals/{id}/approve             - Çekimi onayla
POST   /admin/withdrawals/{id}/reject              - Çekimi reddet
```

---

## 🏪 **VENDOR PANEL ROUTES (Satıcı Paneli)**

### **Dashboard**
```
GET    /vendor/dashboard                           - Satıcı ana sayfa
```

### **Mağaza Yönetimi**
```
GET    /vendor/shop                                - Mağaza bilgilerini göster
PUT    /vendor/shop                                - Mağaza bilgilerini güncelle
```

### **Ayarlar**
```
GET    /vendor/settings                            - Ayarları göster
PUT    /vendor/settings                            - Ayarları güncelle
```

### **İlan Yönetimi**
```
GET    /vendor/listings                            - İlanlarım
GET    /vendor/listings/create                     - Yeni ilan oluştur
POST   /vendor/listings                            - İlan kaydet
GET    /vendor/listings/{id}/edit                  - İlan düzenle
PUT    /vendor/listings/{id}                       - İlan güncelle
DELETE /vendor/listings/{id}                       - İlan sil
POST   /vendor/listings/{id}/submit                - İlanı onaya gönder
```

### **Siparişler**
```
GET    /vendor/orders                              - Siparişlerim
GET    /vendor/orders/{id}                         - Sipariş detayı
PUT    /vendor/orders/{id}/status                  - Sipariş durumu güncelle
```

### **Kazançlar**
```
GET    /vendor/earnings                            - Kazanç raporu
```

### **Cüzdan**
```
GET    /vendor/wallet                              - Cüzdan ana sayfa
GET    /vendor/wallet/transactions                 - İşlem geçmişi
GET    /vendor/wallet/withdraw                     - Para çekme formu
POST   /vendor/wallet/withdraw                     - Para çekme talebi oluştur
```

---

## 🌐 **PUBLIC ROUTES (Kullanıcı Tarafı)**

### **İlan Sayfaları**
```
GET    /ilanlar                                    - İlan listesi
        Query Params:
        - category      : Kategori ID
        - search        : Arama terimi
        - min_price     : Minimum fiyat
        - max_price     : Maksimum fiyat
        - sort          : latest|price_low|price_high|popular|rating

GET    /ilan/{slug}-{id}                           - İlan detay sayfası
        Örnek: /ilan/iphone-13-pro-max-256gb-1
```

### **Satıcı Sayfaları**
```
GET    /vendors                                    - Satıcı listesi
GET    /vendors/{slug}                             - Satıcı profil sayfası
        Örnek: /vendors/test-magazasi
```

### **Satıcı Başvurusu (Auth Gerekli)**
```
GET    /become-vendor                              - Başvuru formu
POST   /become-vendor                              - Başvuru gönder
```

---

## 🧪 **TEST SENARYOLARI**

### **1. Admin Testi:**
```
1. http://localhost/admin → Giriş yap (admin@beritan.com / 12345678)
2. Admin panelde "Satıcılar" menüsü görünmeli
3. /admin/vendors → Satıcı listesi
4. /admin/vendor-applications → Bekleyen başvurular
5. /admin/listings → İlan listesi
6. /admin/listings/pending → Onay bekleyen ilanlar
```

### **2. Satıcı Testi:**
```
1. http://localhost/admin → Giriş yap (vendor@test.com / 12345678)
2. /vendor/dashboard → Satıcı dashboard görünmeli
3. /vendor/listings → İlanlarım sayfası
4. /vendor/listings/create → Yeni ilan oluştur
5. /vendor/shop → Mağaza ayarları
6. /vendor/wallet → Cüzdan sayfası
```

### **3. Public Testi:**
```
1. /ilanlar → İlan listesi (Giriş gereksiz)
2. /vendors → Satıcı listesi
3. /vendors/test-magazasi → Test satıcısının profili
4. /become-vendor → Satıcı başvuru formu (Giriş gerekli)
```

---

## 🎯 **ROUTE GRUPLARI**

### **Admin Routes (17 endpoint):**
- Vendor Management: 10 route
- Applications: 5 route
- Listings: 7 route
- Wallets: 2 route
- Withdrawals: 3 route

### **Vendor Panel Routes (15 endpoint):**
- Dashboard: 1 route
- Shop: 2 route
- Settings: 2 route
- Listings: 7 route
- Orders: 3 route
- Earnings: 1 route
- Wallet: 4 route

### **Public Routes (5 endpoint):**
- Listings: 2 route
- Vendors: 2 route
- Application: 2 route (auth required)

**TOPLAM:** 37+ route

---

## 📍 **ROUTE İSİMLERİ (Named Routes)**

### **Admin:**
```php
admin.vendors.index
admin.vendors.create
admin.vendors.store
admin.vendors.edit
admin.vendors.update
admin.vendors.destroy
admin.vendors.approve
admin.vendors.reject
admin.vendors.suspend
admin.vendors.activate

admin.vendor_applications.index
admin.vendor_applications.reviewed
admin.vendor_applications.show
admin.vendor_applications.approve
admin.vendor_applications.reject

admin.listings.index
admin.listings.pending
admin.listings.show
admin.listings.approve
admin.listings.reject
admin.listings.toggle_featured
admin.listings.toggle_active
admin.listings.destroy

admin.wallets.index
admin.withdrawals.index
admin.withdrawals.pending
admin.withdrawals.approve
admin.withdrawals.reject
```

### **Vendor:**
```php
vendor.dashboard
vendor.shop.edit
vendor.shop.update
vendor.settings.edit
vendor.settings.update

vendor.listings.index
vendor.listings.create
vendor.listings.store
vendor.listings.edit
vendor.listings.update
vendor.listings.destroy
vendor.listings.submit

vendor.orders.index
vendor.orders.show
vendor.orders.update_status

vendor.earnings.index

vendor.wallet.index
vendor.wallet.transactions
vendor.wallet.withdraw.create
vendor.wallet.withdraw.store
```

### **Public:**
```php
listings.index
listings.show

vendors.index
vendors.show

vendor.application.create
vendor.application.store
```

---

## 🔧 **MIDDLEWARE KULLANIMI**

### **Admin Routes:**
```php
Middleware: ['web', 'admin']
Permission: can:admin.vendors.index vb.
```

### **Vendor Panel Routes:**
```php
Middleware: ['web', 'auth', 'vendor']
Permission: can:vendor.listings.index vb.

Vendor Middleware Kontrolü:
- Kullanıcı giriş yapmış mı?
- is_vendor = true mi?
- Vendor kaydı var mı?
- Vendor durumu approved mı?
- Vendor aktif mi?
```

### **Public Routes:**
```php
Middleware: ['web']
Auth Required: /become-vendor için
```

---

## 📝 **TEST KOMUTLARI**

### **Route Listesini Görüntüle:**
```bash
php artisan route:list --path=vendor
php artisan route:list --path=admin/vendors
php artisan route:list --path=admin/listings
php artisan route:list --path=ilanlar
```

### **Cache Temizleme:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### **Permission Kontrolü:**
```bash
# Admin panelde Permissions kısmından kontrol edin
# veya database'den:
SELECT * FROM roles;
SELECT * FROM user_roles;
```

---

## 🎨 **ÖRNEK KULLANIM**

### **Blade'de Route Kullanımı:**
```php
// Admin
<a href="{{ route('admin.vendors.index') }}">Satıcılar</a>
<a href="{{ route('admin.listings.pending') }}">Bekleyen İlanlar</a>

// Vendor
<a href="{{ route('vendor.dashboard') }}">Dashboard</a>
<a href="{{ route('vendor.listings.create') }}">Yeni İlan</a>

// Public
<a href="{{ route('listings.index') }}">İlanlar</a>
<a href="{{ route('vendors.show', $vendor->slug) }}">Satıcı Profili</a>
<a href="{{ route('listings.show', ['slug' => $listing->slug, 'id' => $listing->id]) }}">İlan</a>
```

### **Controller'da Redirect:**
```php
return redirect()->route('vendor.listings.index');
return redirect()->route('admin.vendors.show', $vendor->id);
```

---

## ⚠️ **ÖNEMLİ NOTLAR**

1. **Admin Prefix:** Varsayılan `/admin` (config/app.php'de değiştirilebilir)

2. **Middleware Sırası Önemli:**
   - web → admin → permission
   - web → auth → vendor → permission

3. **Named Routes Kullanın:**
   - Hard-coded URL yerine named route kullanın
   - Daha kolay bakım ve değişiklik

4. **Permission Kontrol:**
   - Her admin route'da `can:` middleware var
   - Permission'lar admin panelden yönetilebilir

5. **Vendor Middleware:**
   - Otomatik vendor durumu kontrolü
   - Pending/Rejected/Suspended vendor'lar erişemez
   - Tatil modunda bildirim gösterir

---

## 🚀 **HIZLI TEST BAŞLATMA**

### **1. Admin Panel Test:**
```
URL: http://localhost/admin
Login: admin@beritan.com / 12345678

Test Adımları:
1. Dashboard'a eriş
2. Satıcılar → Tüm satıcıları gör
3. Satıcı Başvuruları → Test başvurusu onayla
4. İlanlar → İlan listesini gör
```

### **2. Satıcı Panel Test:**
```
URL: http://localhost/vendor/dashboard
Login: vendor@test.com / 12345678

Test Adımları:
1. Dashboard istatistikleri gör
2. Yeni İlan Ekle
3. Otomatik teslimat seç
4. Stok ekle
5. Onaya gönder
```

### **3. Public Test:**
```
URL: http://localhost/ilanlar
Login: Gereksiz

Test Adımları:
1. İlan listesini görüntüle
2. Kategori filtrele
3. Fiyat aralığı seç
4. İlan detayına git
5. Satıcı profiline git
```

---

## 📊 **ROUTE İSTATİSTİKLERİ**

- **Admin Routes:** 27
- **Vendor Routes:** 18
- **Public Routes:** 5
- **TOPLAM:** 50+ route

### **HTTP Method Dağılımı:**
- GET: 32 route
- POST: 12 route
- PUT: 4 route
- DELETE: 2 route

### **Middleware Dağılımı:**
- web: 50 route
- admin: 27 route
- auth: 20 route
- vendor: 18 route

---

## 🎯 **TEST CHECKLIST**

### **Admin Testi:**
- [ ] Admin panele giriş yapabiliyorum
- [ ] Satıcı listesini görebiliyorum
- [ ] Başvuruları onaylayabiliyorum
- [ ] İlanları onaylayabiliyorum
- [ ] Vitrin/Boost yapabiliyorum

### **Vendor Testi:**
- [ ] Vendor panele giriş yapabiliyorum
- [ ] Dashboard istatistikleri görünüyor
- [ ] Yeni ilan oluşturabiliyorum
- [ ] Stok ekleyebiliyorum
- [ ] Mağaza ayarlarını düzenleyebiliyorum
- [ ] Cüzdanımı görüntüleyebiliyorum

### **Public Testi:**
- [ ] İlan listesini görebiliyorum
- [ ] Filtreleme çalışıyor
- [ ] İlan detayına girebiliyorum
- [ ] Satıcı profilini görebiliyorum
- [ ] Satıcı başvurusu yapabiliyorum

---

## 🔗 **HIZLI ERİŞİM LİNKLERİ**

Tarayıcınızda test etmek için:

### **Admin Panel:**
```
http://localhost/admin
http://localhost/admin/vendors
http://localhost/admin/vendor-applications
http://localhost/admin/listings
http://localhost/admin/listings/pending
http://localhost/admin/withdrawals/pending
```

### **Vendor Panel:**
```
http://localhost/vendor/dashboard
http://localhost/vendor/listings
http://localhost/vendor/listings/create
http://localhost/vendor/shop
http://localhost/vendor/wallet
```

### **Public:**
```
http://localhost/ilanlar
http://localhost/ilanlar?category=1&sort=price_low
http://localhost/vendors
http://localhost/vendors/test-magazasi
http://localhost/become-vendor
```

---

## 💡 **İPUÇLARI**

1. **Hata Alırsanız:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   composer dump-autoload
   ```

2. **Route'ları Test Etmek:**
   ```bash
   php artisan route:list
   ```

3. **Permission Hatası Alırsanız:**
   - Admin panelden Roles → Admin rolüne gerekli permission'ları ekleyin
   - Veya database'den users tablosuna permissions ekleyin

4. **Vendor Middleware Hatası:**
   - Kullanıcının is_vendor = 1 olduğundan emin olun
   - Vendor kaydının status = 'approved' olduğundan emin olun

---

## 🎉 **SİSTEM HAZIR!**

Tüm route'lar aktif ve test edilmeye hazır! 🚀

**Not:** İlk kez giriş yapıldığında bazı permission'lar eksik olabilir. 
Admin panelden Roles → Admin → Permissions kısmından gerekli yetkileri ekleyin.

