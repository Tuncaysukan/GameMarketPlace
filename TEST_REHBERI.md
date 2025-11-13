# 🧪 BERİTAN PAZARYERI - HIZLI TEST REHBERİ

## 🎯 **HEMEN TEST ETMEK İÇİN**

### **✅ HAZIR KULLANICILAR**

Admin ve test kullanıcıları oluşturuldu:

```
👤 Admin:    admin@beritan.com / 12345678
🏪 Satıcı:   vendor@test.com / 12345678
🛒 Müşteri:  customer@test.com / 12345678
```

---

## 🚀 **HIZLI TEST ADIMLARI**

### **1️⃣ ADMIN PANELİ TEST (2 dakika)**

```
1. Tarayıcıda aç: http://localhost/admin
2. Giriş yap: admin@beritan.com / 12345678
3. Sol menüde "Pazaryeri" grubu görünmeli
4. Test et:
   ✓ Satıcılar → Test Mağazası görünmeli
   ✓ Satıcı Başvuruları → Başvuruları onayla
   ✓ İlanlar → İlan listesi (henüz boş olabilir)
```

**Beklenen Sonuç:** ✅ Admin paneli açılıyor, menüler görünüyor

---

### **2️⃣ SATICI PANELİ TEST (5 dakika)**

```
1. Tarayıcıda aç: http://localhost/vendor/dashboard
2. Giriş yap: vendor@test.com / 12345678
3. Dashboard açılmalı - İstatistikler görünmeli
4. Test et:
   ✓ "Yeni İlan Ekle" butonuna tıkla
   ✓ Kategori seç
   ✓ Başlık: "Test iPhone 13"
   ✓ Açıklama: "Test ürünüdür"
   ✓ Fiyat: 25000
   ✓ Teslimat: Otomatik seç
   ✓ Stok ekle: "XXXX-YYYY-ZZZZ"
   ✓ Kaydet
   ✓ Onaya gönder
```

**Beklenen Sonuç:** ✅ İlan oluşturuldu, taslak olarak kaydedildi

---

### **3️⃣ İLAN ONAYLAMA TEST (2 dakika)**

```
1. Admin olarak giriş yap
2. İlanlar → Onay Bekleyenler
3. Test iPhone 13 ilanını gör
4. Onayla butonuna tıkla
```

**Beklenen Sonuç:** ✅ İlan onaylandı, public tarafta görünür oldu

---

### **4️⃣ PUBLIC TARAF TEST (2 dakika)**

```
1. Tarayıcıda aç: http://localhost/ilanlar
2. İlan listesini gör
3. Test iPhone 13 ilanına tıkla
4. İlan detay sayfası açılmalı
5. Test et:
   ✓ Görseller görünüyor mu?
   ✓ Fiyat doğru mu?
   ✓ Satıcı bilgileri görünüyor mu?
   ✓ "Sepete Ekle" butonu var mı?
```

**Beklenen Sonuç:** ✅ İlan detay sayfası açılıyor

---

## 📋 **DETAYLI TEST PLANI**

### **A. Admin Panel Testleri**

#### **Satıcı Yönetimi:**
- [ ] Satıcı listesi görünüyor
- [ ] Satıcı başvuruları listeleniyor
- [ ] Başvuru onaylama çalışıyor
- [ ] Başvuru reddetme çalışıyor
- [ ] Satıcı askıya alma çalışıyor

#### **İlan Yönetimi:**
- [ ] İlan listesi görünüyor
- [ ] Bekleyen ilanlar listeleniyor
- [ ] İlan onaylama çalışıyor
- [ ] İlan reddetme çalışıyor
- [ ] Vitrine ekleme çalışıyor

#### **Cüzdan Yönetimi:**
- [ ] Cüzdan listesi görünüyor
- [ ] Çekim talepleri listeleniyor
- [ ] Çekim onaylama çalışıyor

---

### **B. Satıcı Panel Testleri**

#### **Dashboard:**
- [ ] İstatistikler görünüyor
- [ ] Son ilanlar listeleniyor
- [ ] Hızlı erişim butonları çalışıyor

#### **İlan İşlemleri:**
- [ ] İlan oluşturma formu açılıyor
- [ ] Kategori seçimi çalışıyor
- [ ] Görsel yükleme çalışıyor (Max 10)
- [ ] Otomatik teslimat seçimi çalışıyor
- [ ] Stok ekleme çalışıyor
- [ ] Manuel teslimat seçimi çalışıyor
- [ ] Kaydetme çalışıyor
- [ ] Onaya gönderme çalışıyor
- [ ] İlan düzenleme çalışıyor
- [ ] İlan silme çalışıyor

#### **Mağaza Ayarları:**
- [ ] Mağaza bilgileri düzenlenebiliyor
- [ ] Logo yüklenebiliyor
- [ ] Banner yüklenebiliyor
- [ ] Sosyal medya linkleri eklenebiliyor

#### **Satıcı Ayarları:**
- [ ] Bildirim ayarları çalışıyor
- [ ] Tatil modu aktif edilebiliyor
- [ ] İade politikası ayarlanabiliyor

#### **Cüzdan:**
- [ ] Bakiye görünüyor
- [ ] İşlem geçmişi listeleniyor
- [ ] Para çekme formu açılıyor

---

### **C. Public Taraf Testleri**

#### **İlan Listeleme:**
- [ ] İlanlar görünüyor
- [ ] Kategori filtresi çalışıyor
- [ ] Fiyat filtresi çalışıyor
- [ ] Arama çalışıyor
- [ ] Sıralama çalışıyor
- [ ] Pagination çalışıyor

#### **İlan Detay:**
- [ ] İlan detayı açılıyor
- [ ] Görsel galerisi çalışıyor
- [ ] Satıcı bilgileri görünüyor
- [ ] Teslimat bilgisi görünüyor
- [ ] Benzer ilanlar görünüyor

#### **Satıcı Profil:**
- [ ] Satıcı listesi görünüyor
- [ ] Satıcı profili açılıyor
- [ ] Satıcının ilanları listeleniyor
- [ ] İstatistikler görünüyor

#### **Satıcı Başvurusu:**
- [ ] Başvuru formu açılıyor
- [ ] Form validasyonu çalışıyor
- [ ] Başvuru gönderilebiliyor

---

## ⚡ **HIZLI SORUN GİDERME**

### **Sorun 1: "Route not found" hatası**
```bash
Çözüm:
php artisan route:clear
php artisan cache:clear
composer dump-autoload
```

### **Sorun 2: "Permission denied" hatası**
```
Çözüm:
- Admin panelden Roles → Admin rolüne tüm permission'ları ver
- veya database'de users tablosunda permissions = {"admin": true} yap
```

### **Sorun 3: "Vendor middleware" hatası**
```
Çözüm:
- Users tablosunda is_vendor = 1 olmalı
- Vendors tablosunda kayıt olmalı
- Vendor.status = 'approved' olmalı
```

### **Sorun 4: "404 Not Found"**
```
Çözüm:
- .htaccess dosyası var mı kontrol et
- Apache mod_rewrite aktif mi?
- APP_URL .env dosyasında doğru mu?
```

---

## 📱 **TAVSİYE EDİLEN TEST SIRASI**

1. **Önce Admin** → Sistem kontrolü
2. **Sonra Vendor** → İlan oluşturma
3. **Admin'de Onay** → İlanı yayınla
4. **Public'te Görüntüle** → Son kullanıcı deneyimi

---

## 🎊 **TEBRİKLER!**

Pazaryeri sisteminiz tamamen hazır ve test edilmeye başlayabilirsiniz! 

Herhangi bir sorunla karşılaşırsanız:
1. PAZARYERI_DOKUMAN.md → Detaylı dökümantasyon
2. ROUTE_LISTESI.md → Tüm route'lar
3. Migration logları → Veritabanı kontrolü

**Başarılar! 🚀**

