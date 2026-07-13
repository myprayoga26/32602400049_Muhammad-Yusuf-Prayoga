<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "literia_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$queries = [
    "CREATE TABLE IF NOT EXISTS reviews (
        id_review INT AUTO_INCREMENT PRIMARY KEY,
        id_buku INT NOT NULL,
        id_anggota INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        komentar TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
        FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
        UNIQUE KEY uniq_review (id_anggota, id_buku)
    )",
    "ALTER TABLE anggota ADD COLUMN IF NOT EXISTS reading_goal INT DEFAULT 0"
];

foreach ($queries as $sql) {
    if ($mysqli->query($sql) === TRUE) {
        echo "Query executed successfully.\n";
    } else {
        echo "Error executing query: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
