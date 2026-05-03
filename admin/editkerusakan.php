<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location: ../login.php"); // Path Diperbaiki
    exit;
}

include 'koneksi.php'; // Path Diperbaiki

// Logika untuk MEMPROSES form saat disubmit
if(isset($_POST['ubah'])){
    $id_penyakit = $_POST['id_penyakit'];
    $nama_penyakit = $_POST['nama_penyakit'];
    $solusi = $_POST['solusi'];

    if(empty($nama_penyakit) || empty($solusi)) {
        echo "<script>alert('Nama kerusakan dan solusi tidak boleh kosong!'); window.history.back();</script>";
    } else {
        $sql = "UPDATE tbl_penyakit SET nama_penyakit = ?, solusi = ? WHERE id_penyakit = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nama_penyakit, $solusi, $id_penyakit);

        if(mysqli_stmt_execute($stmt)){
            $_SESSION['pesan_sukses'] = "Data kerusakan berhasil diperbarui.";
            header("Location: kerusakan.php"); // Path Diperbaiki
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui data. Silakan coba lagi.'); window.history.back();</script>";
        }
    }
}


// Logika untuk MENGAMBIL data yang akan diedit dari database
if(!isset($_GET['id_penyakit'])) {
    header("location: kerusakan.php"); // Path Diperbaiki
    exit;
}
$id_edit = $_GET['id_penyakit'];

$sql_select = "SELECT * FROM tbl_penyakit WHERE id_penyakit = ?";
$stmt_select = mysqli_prepare($koneksi, $sql_select);
mysqli_stmt_bind_param($stmt_select, "s", $id_edit);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$d = mysqli_fetch_assoc($result);

if(!$d) {
    echo "Data tidak ditemukan.";
    exit;
}

// Mengambil data admin yang login
$data_admin = mysqli_query($koneksi, "SELECT * FROM user WHERE username='{$_SESSION['username']}'");
$admin = mysqli_fetch_array($data_admin);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Data Kerusakan - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/baron" type="text/css"/>
    <style>
        .sidebar-brand-text {
            font-family: 'BaronNeueRegular', sans-serif !important;
            line-height: 0.8 !important;
            text-transform: uppercase;
            font-weight: bold !important;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon"><i class="fas fa-motorcycle"></i></div>
                <div class="sidebar-brand-text mx-3">
                    <div style="font-size: 1.2rem;">SIPAKAR</div>
                    <div style="font-size: 1.2rem;">MOTOR</div>
                </div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Manajemen Data</div>
            <li class="nav-item active"><a class="nav-link" href="kerusakan.php"><i class="fas fa-fw fa-oil-can"></i><span>Data Kerusakan</span></a></li>
            <li class="nav-item"><a class="nav-link" href="gejala.php"><i class="fas fa-fw fa-list-alt"></i><span>Data Gejala</span></a></li>
            <li class="nav-item"><a class="nav-link" href="aturan.php"><i class="fas fa-fw fa-cogs"></i><span>Basis Aturan</span></a></li>
            <li class="nav-item"><a class="nav-link" href="riwayat.php"><i class="fas fa-fw fa-history"></i><span>Riwayat Diagnosa</span></a></li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($admin['username']);?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Form Edit Data Kerusakan</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="POST" action="editkerusakan.php?id_penyakit=<?= htmlspecialchars($d['id_penyakit']); ?>">
                                <div class="form-group">
                                    <label for="id_penyakit">Kode Kerusakan</label>
                                    <input type="text" class="form-control" id="id_penyakit" name="id_penyakit" value="<?= htmlspecialchars($d['id_penyakit']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nama_penyakit">Nama Kerusakan</label>
                                    <input type="text" class="form-control" id="nama_penyakit" name="nama_penyakit" value="<?= htmlspecialchars($d['nama_penyakit']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="solusi">Solusi</label>
                                    <textarea class="form-control" id="solusi" name="solusi" rows="4" required><?= htmlspecialchars($d['solusi']); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" name="ubah">Simpan Perubahan</button>
                                <a href="kerusakan.php" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistem Pakar Diagnosa Motor 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Siap untuk Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah ini jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>