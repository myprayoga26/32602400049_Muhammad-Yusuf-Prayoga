<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "literia_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Cek apakah admin sudah ada
$result = $mysqli->query("SELECT * FROM admin WHERE username = 'admin'");
if ($result->num_rows == 0) {
    // Insert admin default
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("INSERT INTO admin (username, password, nama_lengkap) VALUES ('admin', ?, 'Administrator')");
    $stmt->bind_param("s", $password);
    
    if ($stmt->execute()) {
        echo "Akun admin berhasil dibuat!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Gagal membuat admin: " . $stmt->error;
    }
} else {
    echo "Akun admin dengan username 'admin' sudah ada di database.\n";
}
$mysqli->close();
