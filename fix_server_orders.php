<?php

// .env dosyasından veritabanı bilgilerini oku
$env = file_get_contents('.env');
preg_match('/DB_HOST=(.*)/', $env, $host);
preg_match('/DB_DATABASE=(.*)/', $env, $database);
preg_match('/DB_USERNAME=(.*)/', $env, $username);
preg_match('/DB_PASSWORD=(.*)/', $env, $password);

$host = isset($host[1]) ? trim($host[1]) : 'localhost';
$database = isset($database[1]) ? trim($database[1]) : '';
$username = isset($username[1]) ? trim($username[1]) : '';
$password = isset($password[1]) ? trim($password[1]) : '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== SUNUCU SİPARİŞ DÜZELTMESİ ===\n\n";

    // Önce mevcut durumu göster
    echo "Mevcut sipariş durumları:\n";
    $stmt = $pdo->query("
        SELECT status, COUNT(*) as count
        FROM orders
        GROUP BY status
        ORDER BY count DESC
    ");

    while ($row = $stmt->fetch()) {
        echo "- {$row['status']}: {$row['count']} adet\n";
    }

    echo "\n";

    // Pending payment olanları completed yap
    $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE status IN ('pending_payment', 'pending')");
    $result = $stmt->execute();
    $affected = $stmt->rowCount();

    echo "✅ {$affected} adet sipariş 'completed' olarak güncellendi.\n\n";

    // Güncellenmiş durumu göster
    echo "Güncellenmiş sipariş durumları:\n";
    $stmt = $pdo->query("
        SELECT status, COUNT(*) as count
        FROM orders
        GROUP BY status
        ORDER BY count DESC
    ");

    while ($row = $stmt->fetch()) {
        echo "- {$row['status']}: {$row['count']} adet\n";
    }

    echo "\n";

    // Son 5 siparişi göster
    echo "Son 5 sipariş:\n";
    $stmt = $pdo->query("SELECT id, status, payment_method, created_at FROM orders ORDER BY id DESC LIMIT 5");
    while ($row = $stmt->fetch()) {
        echo "#{$row['id']} - {$row['status']} - {$row['payment_method']} - {$row['created_at']}\n";
    }

    echo "\n✅ TAMAMLANDI! Artık tüm siparişler 'Teslim Edildi' gösterecek.\n";

} catch (PDOException $e) {
    echo "❌ Veritabanı hatası: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
