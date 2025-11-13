-- Tüm pending_payment durumundaki siparişleri completed yap
UPDATE orders
SET status = 'completed'
WHERE status = 'pending_payment';

-- Son 10 siparişi kontrol et
SELECT id, status, payment_method, created_at
FROM orders
ORDER BY id DESC
LIMIT 10;
