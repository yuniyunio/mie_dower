<?php
$koneksi = mysqli_connect("localhost", "root", "", "dbpemesanan1");

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
