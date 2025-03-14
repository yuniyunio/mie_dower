<?php  
include('koneksi.php');
session_start();
if (!isset($_SESSION['login_user'])) {
  header("Location: login.php"); // Redirect ke halaman login jika belum login
  exit;
}
?>

<?php 
if(isset($_POST["konfirm"]))
{
    $nama = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $no_hp = isset($_POST['nomor_hp']) ? $_POST['nomor_hp'] : '';
    $tanggal_pemesanan = date("Y-m-d");

    // Simpan data pemesan ke database
    $insert = mysqli_query($koneksi, "INSERT INTO pemesanan (nama_lengkap, alamat, nomor_hp, tanggal_pemesanan, total_belanja) 
                                      VALUES ('$nama', '$alamat', '$no_hp', '$tanggal_pemesanan', '0')");

    if ($insert) {
        // Mendapatkan ID pemesanan terbaru
        $id_terbaru = $koneksi->insert_id;

        // Simpan data pesanan ke tabel pemesanan_produk
        $totalbelanja = 0;
        foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) {
            $ambil = mysqli_query($koneksi, "SELECT harga FROM produk WHERE id_menu='$id_menu'");
            $produk = mysqli_fetch_assoc($ambil);
            $subharga = $produk["harga"] * $jumlah;
            $totalbelanja += $subharga;

            $insert_produk = mysqli_query($koneksi, "INSERT INTO pemesanan_produk (id_pemesanan, id_menu, jumlah) 
            VALUES ('$id_terbaru', '$id_menu', '$jumlah')");}

        // Update total belanja pada pemesanan
        mysqli_query($koneksi, "UPDATE pemesanan SET total_belanja='$totalbelanja' WHERE id_pemesanan='$id_terbaru'");

        // Kosongkan pesanan di session
        unset($_SESSION["pesanan"]);

        // Redirect ke halaman nota
        echo "<script>alert('Pemesanan Sukses!');</script>";
        echo "<script>location='menu_pembeli.php'</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MIE DOWER</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="judul-pesanan mt-5">
        <h3 class="text-center font-weight-bold">PESANAN ANDA</h3>
    </div>

    <!-- Form Data User + Pesanan -->
    <form method="POST" action="">
        <div class="user-details mb-4">
            <h5>Data Diri Anda</h5>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" name="alamat" required>
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" class="form-control" name="nomor_hp" required>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Pesanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subharga</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor=1; 
                $totalbelanja = 0;
                if (!empty($_SESSION["pesanan"])) :
                    foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) :
                        $ambil = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_menu='$id_menu'");
                        $pecah = $ambil->fetch_assoc();
                        $subharga = $pecah["harga"] * $jumlah;
                        $totalbelanja += $subharga;
                ?>
                <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $pecah["nama_menu"]; ?></td>
                    <td>Rp. <?= number_format($pecah["harga"]); ?></td>
                    <td><?= $jumlah; ?></td>
                    <td>Rp. <?= number_format($subharga); ?></td>
                    <td><a href="hapus_pesanan.php?id_menu=<?= $id_menu ?>" class="badge badge-danger">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total Belanja</th>
                    <th colspan="2">Rp. <?= number_format($totalbelanja) ?></th>
                </tr>
            </tfoot>
            <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Pesanan kosong, silahkan pilih menu terlebih dahulu.</td>
            </tr>
            <?php endif; ?>
        </table>

        <a href="menu_pembeli.php" class="btn btn-primary btn-sm">Lihat Menu</a>
        <button class="btn btn-success btn-sm" name="konfirm">Konfirmasi Pesanan</button>
    </form>
</div>
    <!-- Akhir Menu -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" 
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" 
      integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
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
<?php  ?>
