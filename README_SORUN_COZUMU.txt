================================================================================
BERİTAN PAZARYERI - HIZLI SORUN ÇÖZÜMÜ
================================================================================

🔴 SORUN 1: GİRİŞ YAPAMIYORUM
================================================================================

ÇÖZÜM:
1. quick_admin_fix.sql dosyasını veritabanında çalıştırın
2. Şifre hash'i: password

ŞİFRE:
- Admin Email: admin@beritan.com
- Şifre: password

================================================================================
🔴 SORUN 2: /account SAYFASINA YÖNLENDİRİLİYOR
================================================================================

SEBEP: Yanlış giriş sayfası kullanıyorsunuz!

❌ YANLIŞ URL: http://127.0.0.1:8000/login       → Müşteri paneli
✅ DOĞRU URL:  http://127.0.0.1:8000/admin/login → Admin paneli

DİKKAT: "admin/login" yazın, sadece "login" YAZMAYIN!

================================================================================
🔴 SORUN 3: SİDEBAR'DA MARKETPLACE MENÜSÜ YOK
================================================================================

ÇÖZÜM:
1. Cache temizleyin:
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear

2. Tarayıcıda CTRL + SHIFT + R (Hard refresh)

3. Çıkış yapıp tekrar giriş yapın

================================================================================
✅ DOĞRU GİRİŞ ADIMLARI
================================================================================

1. Tarayıcı aç
2. http://127.0.0.1:8000/admin/login  ← DİKKAT: /admin/login
3. Email: admin@beritan.com
4. Şifre: password
5. Login

BAŞARILI GİRİŞTE:
- URL: http://127.0.0.1:8000/admin/dashboard olmalı
- Sidebar'da "Marketplace" menüsü görünmeli

================================================================================
🔑 TÜM GİRİŞ BİLGİLERİ
================================================================================

ADMIN:
URL: http://127.0.0.1:8000/admin/login
Email: admin@beritan.com
Şifre: password

SATICI (Vendor):
URL: http://127.0.0.1:8000/admin/login
Email: vendor@test.com
Şifre: password

MÜŞTERİ (Customer):
URL: http://127.0.0.1:8000/login
Email: customer@test.com
Şifre: password

================================================================================
📝 NOT
================================================================================

- Admin ve Satıcı → /admin/login kullanır
- Müşteri → /login kullanır
- Sidebar sadece admin panelde görünür (/admin/...)

================================================================================

