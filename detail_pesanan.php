<?php 
include('koneksi.php');
session_start();
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID pemesanan tidak ditemukan!'); location='pesanan.php';</script>";
    exit;
}

$id_pemesanan = $_GET['id'];

// Ambil data pelanggan berdasarkan ID
$query_pelanggan = $koneksi->query("SELECT * FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");
$data_pelanggan = $query_pelanggan->fetch_assoc();

// Ambil detail pesanan produk berdasarkan ID
$query_produk = $koneksi->query("SELECT * FROM pemesanan_produk 
    JOIN produk ON pemesanan_produk.id_menu = produk.id_menu 
    WHERE pemesanan_produk.id_pemesanan = '$id_pemesanan'");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MIE DOWER</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">

</head>
<body>
  <!-- Navbar -->
      <nav class="navbar navbar-expand-lg  bg-dark">
        <div class="container">
        <a class="navbar-brand text-white" href="admin.php"><strong>MIE DOWER</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link mr-4" href="admin.php">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="daftar_menu.php">DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="pesanan.php">PESANAN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="logout.php">LOGOUT</a>
            </li>
          </ul>
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->

    <!-- Isi Konten -->
    <div class="container mt-5">
        <h3 class="text-center font-weight-bold mb-4">DATA PESANAN PELANGGAN</h3>

        <!-- Info Pelanggan -->
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>Nomor HP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $data_pelanggan['nama_lengkap']; ?></td>
                    <td><?= $data_pelanggan['alamat']; ?></td>
                    <td><?= $data_pelanggan['nomor_hp']; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Rincian Pesanan -->
        <table class="table table-bordered" id="example">
            <thead class="thead-light">
                <tr>
                    <th>No.</th>
                    <th>ID Pemesanan</th>
                    <th>Nama Pesanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subharga</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor = 1;
                $totalbelanja = 0;
                while ($pecah = $query_produk->fetch_assoc()):
                    $subharga = $pecah['harga'] * $pecah['jumlah'];
                    $totalbelanja += $subharga;
                ?>
                <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $pecah['id_pemesanan_produk']; ?></td>
                    <td><?= $pecah['nama_menu']; ?></td>
                    <td>Rp. <?= number_format($pecah['harga']); ?></td>
                    <td><?= $pecah['jumlah']; ?></td>
                    <td>Rp. <?= number_format($subharga); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">Total Bayar</th>
                    <th>Rp. <?= number_format($totalbelanja); ?></th>
                </tr>
            </tfoot>
        </table>

        <!-- Aksi -->
        <form method="POST" action="">
            <a href="pesanan.php" class="btn btn-success btn-sm">Kembali</a>
            <a href="nota.php?id=<?= $id_pemesanan ?>" class="btn btn-warning btn-sm" target="_blank">Cetak Nota</a>
            <button type="submit" name="bayar" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</button>
        </form>

        <?php 
        if (isset($_POST["bayar"])) {
            $koneksi->query("DELETE FROM pemesanan_produk WHERE id_pemesanan = '$id_pemesanan'");
            $koneksi->query("DELETE FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");
            echo "<script>alert('Pesanan telah dibayar dan dihapus dari daftar!'); location='pesanan.php';</script>";
        }
        ?>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
