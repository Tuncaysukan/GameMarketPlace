-- ========================================
-- BERİTAN PAZARYERI - HIZLI ADMIN OLUŞTURMA
-- ========================================

-- Mevcut admin kullanıcıları sil (varsa)
DELETE FROM user_roles WHERE user_id IN (SELECT id FROM users WHERE email IN ('admin@beritan.com', 'admin@test.com'));
DELETE FROM activations WHERE user_id IN (SELECT id FROM users WHERE email IN ('admin@beritan.com', 'admin@test.com'));
DELETE FROM users WHERE email IN ('admin@beritan.com', 'admin@test.com');

-- Admin kullanıcısı oluştur
INSERT INTO users (first_name, last_name, username, email, phone, password, permissions, created_at, updated_at, is_vendor, vendor_application_pending) 
VALUES (
    'Admin',
    'Beritan',
    'admin',
    'admin@beritan.com',
    '05551234567',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    '{"admin": true}',
    NOW(),
    NOW(),
    0,
    0
);

-- Admin ID'yi al ve kullan
SET @admin_id = LAST_INSERT_ID();

-- Admin rolünü ata (Role ID: 1)
INSERT INTO user_roles (user_id, role_id, created_at, updated_at)
VALUES (@admin_id, 1, NOW(), NOW());

-- Aktivasyon ekle
INSERT INTO activations (user_id, code, completed, completed_at, created_at, updated_at)
VALUES (@admin_id, 'completed', 1, NOW(), NOW(), NOW());

-- ========================================
-- GİRİŞ BİLGİLERİ
-- ========================================
-- URL: http://127.0.0.1:8000/admin/login
-- Email: admin@beritan.com
-- Şifre: password
-- ========================================

SELECT 'Admin kullanıcısı oluşturuldu!' as Status;
SELECT id, email, first_name, last_name FROM users WHERE email = 'admin@beritan.com';

