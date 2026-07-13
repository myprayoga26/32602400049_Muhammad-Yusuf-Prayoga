<?php
$db = new mysqli('localhost', 'root', '', 'literia_db');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Add column if not exists
$check = $db->query("SHOW COLUMNS FROM anggota LIKE 'password'");
if ($check->num_rows == 0) {
    $db->query("ALTER TABLE anggota ADD password VARCHAR(255) NULL");
}

// Set default password '123456' for existing users
$hash = password_hash('123456', PASSWORD_DEFAULT);
$db->query("UPDATE anggota SET password = '$hash' WHERE password IS NULL OR password = ''");

echo "Database updated successfully.\n";
