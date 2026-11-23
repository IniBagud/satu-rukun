<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "saturukun_db");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>