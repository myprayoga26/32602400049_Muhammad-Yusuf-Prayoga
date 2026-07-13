<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "literia_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Update password admin
$password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("UPDATE admin SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $password);

if ($stmt->execute()) {
    echo "Password akun admin berhasil direset!\n";
} else {
    echo "Gagal mereset password: " . $stmt->error;
}
$mysqli->close();
