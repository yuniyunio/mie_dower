<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}

// Pastikan ID pemesanan tersedia
if (!isset($_GET['id'])) {
    die("ID Pemesanan tidak ditemukan.");
}

$id_pemesanan = $_GET['id'];

// Ambil data pemesanan
$query = $koneksi->query("SELECT * FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");
$pemesanan = $query->fetch_assoc();

// Ambil detail pesanan
$ambil = $koneksi->query("SELECT * FROM pemesanan_produk 
    JOIN produk ON pemesanan_produk.id_menu = produk.id_menu 
    WHERE pemesanan_produk.id_pemesanan = '$id_pemesanan'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .nota { width: 80%; margin: auto; padding: 20px; border: 2px solid #000; }
        .nota h2, .nota h4 { text-align: center; }
        .nota table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .nota table, .nota th, .nota td { border: 1px solid black; padding: 10px; text-align: center; }
        .cetak { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="nota">
    <h2>Nota Pemesanan</h2>
    <hr>
    <h4>Data Pemesan</h4>
    <table>
        <tr>
            <td><strong>Nama</strong></td>
            <td><?= $pemesanan['nama_lengkap']; ?></td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td><?= $pemesanan['alamat']; ?></td>
        </tr>
        <tr>
            <td><strong>No. HP</strong></td>
            <td><?= $pemesanan['nomor_hp']; ?></td>
        </tr>
    </table>

    <br>
    <h4>Detail Pesanan</h4>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Pesanan</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $nomor = 1;
            $total = 0;
            while ($pecah = $ambil->fetch_assoc()) { 
                $subharga = $pecah['harga'] * $pecah['jumlah'];
                $total += $subharga;
            ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $pecah['nama_menu']; ?></td>
                <td>Rp. <?= number_format($pecah['harga']); ?></td>
                <td><?= $pecah['jumlah']; ?></td>
                <td>Rp. <?= number_format($subharga); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total Bayar</th>
                <th>Rp. <?= number_format($total); ?></th>
            </tr>
        </tfoot>
    </table>
    
    <br>
    <p><strong>Terima kasih telah memesan di MIE DOWER!</strong></p>
</div>

<div class="cetak">
    <button onclick="window.print()" class="btn btn-primary">Cetak Nota</button>
    <a href="detail_pesanan.php?id=<?= $id_pemesanan; ?>" class="btn btn-secondary">Kembali ke Detail</a>
</div>

</body>
</html>
