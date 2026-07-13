<?php
$db = new PDO('mysql:host=localhost;dbname=literia_app', 'root', '');
$db->exec('CREATE TABLE IF NOT EXISTS user_badges (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    id_anggota INT, 
    badge_name VARCHAR(50), 
    earned_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    UNIQUE KEY user_badge (id_anggota, badge_name)
)');
echo "Table user_badges created.";
