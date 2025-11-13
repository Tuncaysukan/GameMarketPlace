# 🔧 SORUN ÇÖZÜMÜ - GİRİŞ YAPAMAMA

## ⚠️ **SORUNLAR VE ÇÖZÜMLER**

---

## **SORUN 1: Giriş Yapamıyorum**

### **Sebep:**
Şifreler bcrypt ile hash'lenmiş ama veritabanında doğru kaydedilmemiş olabilir.

### **✅ ÇÖZÜM 1: Manuel Kullanıcı Oluşturma**

Veritabanından direkt SQL ile kullanıcı oluşturun:

```sql
-- 1. Admin Kullanıcısı
INSERT INTO users (id, first_name, last_name, username, email, phone, password, permissions, created_at, updated_at, is_vendor, vendor_application_pending) 
VALUES (
    1,
    'Admin',
    'Beritan',
    'admin',
    'admin@beritan.com',
    '05551234567',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Şifre: password
    '{"admin": true}',
    NOW(),
    NOW(),
    0,
    0
);

-- 2. Admin Rolü Ata
INSERT INTO user_roles (user_id, role_id, created_at, updated_at)
VALUES (1, 1, NOW(), NOW());

-- 3. Aktivasyon
INSERT INTO activations (user_id, code, completed, completed_at, created_at, updated_at)
VALUES (1, 'admin_activation', 1, NOW(), NOW(), NOW());

-- 4. Test Satıcı
INSERT INTO users (id, first_name, last_name, username, email, phone, password, permissions, created_at, updated_at, is_vendor, vendor_application_pending) 
VALUES (
    2,
    'Test',
    'Vendor',
    'testvendor',
    'vendor@test.com',
    '05559876543',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Şifre: password
    '{}',
    NOW(),
    NOW(),
    1,
    0
);

-- Vendor Rolü (ID: 2 genellikle customer)
INSERT INTO user_roles (user_id, role_id, created_at, updated_at)
VALUES (2, 2, NOW(), NOW());

-- Aktivasyon
INSERT INTO activations (user_id, code, completed, completed_at, created_at, updated_at)
VALUES (2, 'vendor_activation', 1, NOW(), NOW(), NOW());

-- Vendor Profili
INSERT INTO vendors (user_id, shop_name, slug, description, phone, email, city, country, is_verified, is_active, status, commission_rate, approved_by, approved_at, created_at, updated_at)
VALUES (
    2,
    'Test Mağazası',
    'test-magazasi',
    'Test mağazası açıklaması',
    '05559876543',
    'magaza@test.com',
    'İstanbul',
    'Türkiye',
    1,
    1,
    'approved',
    10.00,
    1,
    NOW(),
    NOW(),
    NOW()
);

-- Vendor Settings
INSERT INTO vendor_settings (vendor_id, email_notifications, new_order_notification, processing_days, accept_returns, return_days, created_at, updated_at)
VALUES (1, 1, 1, 3, 1, 14, NOW(), NOW());

-- Wallet
INSERT INTO wallets (vendor_id, balance, pending_balance, total_earned, currency, created_at, updated_at)
VALUES (1, 1000.00, 0, 1000.00, 'TRY', NOW(), NOW());
```

### **✅ ÇÖZÜM 2: Artisan Komutu ile Kullanıcı Oluştur**

```bash
php artisan user:create admin@beritan.com password Admin Beritan --admin
```

### **✅ ÇÖZÜM 3: Şifre Değiştirme**

Eğer kullanıcı varsa şifreyi değiştirin:

```sql
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@beritan.com';

-- Bu şifre: password
```

---

## **SORUN 2: Admin /account'a Yönlendiriliyor**

### **Sebep:**
İki farklı login sayfası var:
- **Public Login:** `/login` → `/account` (Müşteriler için)
- **Admin Login:** `/admin/login` → `/admin/dashboard` (Admin için)

### **✅ ÇÖZÜM: Doğru URL'yi Kullanın**

**YANLIŞ:** ❌ `http://127.0.0.1:8000/login` (Bu müşteri girişi)

**DOĞRU:** ✅ `http://127.0.0.1:8000/admin/login` (Bu admin girişi)

---

## 🔑 **DOĞRU GİRİŞ BİLGİLERİ**

### **Admin Girişi:**
```
URL: http://127.0.0.1:8000/admin/login
Email: admin@beritan.com
Şifre: password   (veya 12345678 eğer seeder çalıştıysa)
```

### **Vendor Girişi:**
```
URL: http://127.0.0.1:8000/admin/login  (veya /login)
Email: vendor@test.com
Şifre: password
```

---

## 🛠️ **HIZLI TESTÇözüm Adımları**

### **1. Admin Kullanıcısını Kontrol Et:**

Veritabanında çalıştır:

```sql
SELECT id, email, first_name, last_name, is_vendor 
FROM users 
WHERE email = 'admin@beritan.com';
```

### **2. Admin Aktivasyonunu Kontrol Et:**

```sql
SELECT * FROM activations WHERE user_id = 1 AND completed = 1;
```

### **3. Admin Rolünü Kontrol Et:**

```sql
SELECT u.email, r.id as role_id
FROM users u
JOIN user_roles ur ON u.id = ur.user_id  
JOIN roles r ON ur.role_id = r.id
WHERE u.email = 'admin@beritan.com';
```

### **4. Eğer Kullanıcı Yoksa:**

```bash
# Seeder'ı tekrar çalıştır
php artisan db:seed --class="Modules\Vendor\Database\Seeders\VendorDatabaseSeeder" --force
```

---

## 🎯 **GEÇİCİ ÇÖZÜM: Basit Admin Oluştur**

Eğer hiçbiri çalışmazsa, basit bir admin oluşturun:

```sql
-- Önce mevcut admin'i sil (varsa)
DELETE FROM user_roles WHERE user_id = 1;
DELETE FROM activations WHERE user_id = 1;
DELETE FROM users WHERE id = 1;

-- Yeni admin oluştur
INSERT INTO users (id, first_name, last_name, username, email, phone, password, permissions, created_at, updated_at, is_vendor, vendor_application_pending) 
VALUES (
    1,
    'Admin',
    'User',
    'admin',
    'admin@test.com',
    '1234567890',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '{"admin": true}',
    NOW(),
    NOW(),
    0,
    0
);

-- Role ata (Admin role ID genellikle 1'dir)
INSERT INTO user_roles (user_id, role_id, created_at, updated_at)
VALUES (1, 1, NOW(), NOW());

-- Aktivasyon
INSERT INTO activations (user_id, code, completed, completed_at, created_at, updated_at)
VALUES (1, 'completed', 1, NOW(), NOW(), NOW());
```

**Sonra giriş yap:**
- URL: `http://127.0.0.1:8000/admin/login`
- Email: `admin@test.com`
- Şifre: `password`

---

## 📝 **SIDEBAR MENÜSÜ GÖRÜNMÜYORSA**

### **Çözüm 1: Cache Temizle**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

### **Çözüm 2: Sidebar Kontrolü**

Veritabanında settings tablosunda kontrol edin:

```sql
SELECT * FROM settings WHERE key = 'supported_locales';
```

### **Çözüm 3: Translation Dosyasını Kontrol Et**

`modules/Admin/Resources/lang/en/sidebar.php` dosyasında `marketplace` key'i olmalı.

---

## 🎯 **TEST KONTROLÜ**

### **1. Giriş Testi:**

```bash
# Browser'da:
http://127.0.0.1:8000/admin/login

# Credentials:
admin@test.com / password
```

### **2. Dashboard Kontrolü:**

Başarılı girişte şu sayfaya yönlendirilmelisiniz:
```
http://127.0.0.1:8000/admin/dashboard
```

### **3. Sidebar Kontrolü:**

Sol menüde şunlar görünmeli:
- Dashboard
- **Marketplace** (Yeni grup)
  - Satıcılar
  - Satıcı Başvuruları
  - İlanlar
- Products
- Orders
- ...diğerleri

---

## 🚨 **ACİL ÇÖZÜM**

Eğer hala giriş yapamıyorsanız:

### **Manuel Şifre Değiştirme:**

```bash
php artisan tinker
# veya SQL:

UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email IN ('admin@beritan.com', 'admin@test.com');
```

Bu şifre hash'i: **password**

---

## ✅ **DOĞRU GİRİŞ ADIMLARI**

1. Tarayıcıyı aç
2. **DİKKAT:** `/admin/login` adresine git (sadece `/login` DEĞİL!)
3. Email: `admin@beritan.com` (veya admin@test.com)
4. Şifre: `password` (veya 12345678)
5. Login butonuna tıkla
6. `/admin/dashboard` sayfasına yönlendirilmelisiniz
7. Sol menüde **Marketplace** grubu görünmeli

---

## 📞 **HALA SORUN VARSA**

Bana şu bilgileri verin:
1. Hangi URL'den giriş yapıyorsunuz? (/login mi /admin/login mi?)
2. Giriş sonrası hangi URL'ye yönlendiriliyor?
3. Sidebar'da hangi menüler görünüyor?
4. Console'da (F12) hata var mı?

Yardımcı olabilirim! 🚀

