<?php 
    session_start();
    if (!isset($_SESSION['login_user'])) {
        header("location: login.php");
    } else {
?>

<!doctype html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>MIEDOWERMENUPEMBELI</title>
</head>
<body>

<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <h1 class="display-4"><span class="font-weight-bold">MIE DOWER</span></h1>
        <hr>
        <p class="lead font-weight-bold">Silahkan Pesan Menu Sesuai Keinginan Anda <br> Enjoy Your Meal</p>
    </div>
</div>
<!-- Akhir Jumbotron -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-dark">
    <div class="container">
        <a class="navbar-brand text-white" href="user.php"><strong>MIE</strong> DOWER</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link mr-4" href="user.php">HOME</a></li>
                <li class="nav-item"><a class="nav-link mr-4" href="menu_pembeli.php">DAFTAR MENU</a></li>
                <li class="nav-item"><a class="nav-link mr-4" href="pesanan_pembeli.php">PESANAN ANDA</a></li>
                <li class="nav-item"><a class="nav-link mr-4" href="logout.php">LOGOUT</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- Akhir Navbar -->

<!-- Menu -->
<div class="container">
    <div class="row mt-3">

        <?php 
        include('koneksi.php');
        $query = mysqli_query($koneksi, 'SELECT * FROM produk');
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        ?>

        <?php foreach ($result as $result) : ?>

        <div class="col-md-3 mt-4">
            <div class="card border-dark">
                <img src="upload/<?php echo $result['gambar'] ?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold"><?php echo $result['nama_menu'] ?></h5>
                    <label class="card-text harga"><strong>Rp.</strong> <?php echo number_format($result['harga']); ?></label><br>
                    <small>Stok: <?php echo $result['stok']; ?></small><br>

                    <?php if ($result['stok'] > 0) { ?>
                        <a href="beli.php?id_menu=<?php echo $result['id_menu']; ?>" class="btn btn-success btn-sm btn-block">BELI</a>
                    <?php } else { ?>
                        <button class="btn btn-danger btn-sm btn-block" disabled>STOK HABIS</button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>
</div>
<!-- Akhir Menu -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
