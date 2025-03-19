<?php 
session_start();
include('koneksi.php');

$id_menu = $_GET['id_menu'];

// Ambil stok dari database
$query = mysqli_query($koneksi, "SELECT stok FROM produk WHERE id_menu = '$id_menu'");
$data = mysqli_fetch_assoc($query);

// Jika stok habis, munculkan alert dan kembali ke menu_pembeli.php
if ($data['stok'] <= 0) {
    echo "<script>alert('Stok habis, tidak bisa memesan!');</script>";
    echo "<script>location='menu_pembeli.php';</script>";
    exit(); // Hentikan eksekusi script agar form tidak muncul
}

// Jika stok masih ada, lanjutkan ke proses pemesanan
if (isset($_SESSION['pesanan'][$id_menu])) {
    $_SESSION['pesanan'][$id_menu] += 1;
} else {
    $_SESSION['pesanan'][$id_menu] = 1;
}

// Kurangi stok di database
$new_stock = $data['stok'] - 1;
mysqli_query($koneksi, "UPDATE produk SET stok = '$new_stock' WHERE id_menu = '$id_menu'");

echo "<script>alert('Produk telah masuk ke pesanan anda');</script>";
echo "<script>location='pesanan_pembeli.php';</script>";
?>
