<?php 
include 'koneksi.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Tambah Menu</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  </head>
  <body>

  <div class="container">
    <h3 class="text-center mt-4 mb-4">SILAHKAN TAMBAH MENU</h3>
    <div class="card p-4 mb-5">

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Nama Menu</label>
          <input type="text" class="form-control" name="nama_menu" required>
        </div>

        <div class="form-group">
          <label>Jenis Menu</label><br>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="jenis_menu" value="Makanan" checked>
            <label class="form-check-label">Makanan</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="jenis_menu" value="Minuman">
            <label class="form-check-label">Minuman</label>
          </div>
        </div>

        <div class="form-group">
          <label>Stok</label>
          <input type="number" class="form-control" name="stok" required>
        </div>

        <div class="form-group">
          <label>Harga Menu</label>
          <input type="number" class="form-control" name="harga" required>
        </div>

        <div class="form-group">
          <label>Foto Menu</label>
          <input type="file" class="form-control-file" name="gambar" required>
        </div>

        <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
        <a href="daftar_menu.php" class="btn btn-secondary ml-2">Kembali</a>
      </form>

      <!-- PHP Proses Simpan -->
      <?php 
      if (isset($_POST['tambah'])) {
        $nama_menu = $_POST['nama_menu'];
        $jenis_menu = $_POST['jenis_menu'];
        $stok = $_POST['stok'];
        $harga = $_POST['harga'];
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = "upload/";

        // Pastikan folder upload ada
        if (!is_dir($folder)) {
          mkdir($folder, 0777, true);
        }

        // Pindahkan file
        if (move_uploaded_file($tmp, $folder . $gambar)) {
          $query = "INSERT INTO produk (nama_menu, jenis_menu, stok, harga, gambar) 
                    VALUES ('$nama_menu', '$jenis_menu', '$stok', '$harga', '$gambar')";
          $insert = mysqli_query($koneksi, $query);

          if ($insert) {
            echo "<div class='alert alert-success mt-3'>Data berhasil ditambahkan!</div>";
            echo "<meta http-equiv='refresh' content='1;url=daftar_menu.php'>";
          } else {
            echo "<div class='alert alert-danger mt-3'>Gagal menyimpan data ke database: " . mysqli_error($koneksi) . "</div>";
          }
        } else {
          echo "<div class='alert alert-warning mt-3'>Upload gambar gagal!</div>";
        }
      }
      ?>
    </div>
  </div>

  <!-- JS Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </body>
</html>
