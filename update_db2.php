<?php
$db = new mysqli('localhost', 'root', '', 'literia_db');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$check = $db->query("SHOW COLUMNS FROM anggota LIKE 'username'");
if ($check->num_rows == 0) {
    $db->query("ALTER TABLE anggota ADD username VARCHAR(50) NULL UNIQUE AFTER nama");
}

$db->query("UPDATE anggota SET username = nomor_induk WHERE username IS NULL OR username = ''");

echo "Database updated successfully with username.\n";
