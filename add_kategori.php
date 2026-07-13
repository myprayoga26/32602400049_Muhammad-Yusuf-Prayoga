<?php
$db = new PDO('mysql:host=127.0.0.1;dbname=literia_db', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $db->exec("ALTER TABLE buku ADD COLUMN kategori VARCHAR(100) DEFAULT 'Umum' AFTER stok");
    echo "Kolom kategori berhasil ditambahkan.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Kolom kategori sudah ada.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
