<?php
require '../services/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $nik      = trim(mysqli_real_escape_string($conn, $_POST['nik']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']); // Plain text (tanpa password_hash)

    // 1. Cek NIK ke tabel master_warga
    $cek_nik = mysqli_query($conn, "SELECT * FROM master_warga WHERE nik = '$nik'");
    if (mysqli_num_rows($cek_nik) === 0) {
        echo "<script>alert('NIK Anda tidak terdaftar sebagai warga RW 001!'); window.location='register.html';</script>";
        exit;
    }

    // 2. Cek apakah Email atau NIK sudah terdaftar
    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' OR nik = '$nik'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Email atau NIK sudah memiliki akun!'); window.location='register.html';</script>";
        exit;
    }

    // 3. Simpan Akun Baru
    $query = "INSERT INTO users (nik, nama, email, password, role) VALUES ('$nik', '$nama', '$email', '$password', 'warga')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.html';</script>";
    } else {
        echo "Gagal mendaftar: " . mysqli_error($conn);
    }
}
?>