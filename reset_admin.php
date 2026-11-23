<?php
require 'koneksi.php';

$email    = 'admin@saturukun.com';
$password = password_hash('admin123', PASSWORD_BCRYPT);

// Update kata sandi admin dengan hash resmi dari PHP
$query = "UPDATE users SET password = '$password' WHERE email = '$email'";

if (mysqli_query($conn, $query)) {
    echo "<h2 style='color: green;'>Password admin berhasil di-update menjadi: admin123</h2>";
    echo "<a href='halaman/login.html'>Klik di sini untuk Login</a>";
} else {
    echo "Gagal memperbarui database: " . mysqli_error($conn);
}
?>