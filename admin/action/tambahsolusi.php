<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location: ../../login.php");
    exit;
}

include '../koneksi.php';

// Logika untuk memproses form saat disubmit
if(isset($_POST['simpan'])){
    $id_penyakit = $_POST['id_penyakit'];
    $solusi = $_POST['solusi'];

    // Validasi sederhana
    if(empty($id_penyakit) || empty($solusi)) {
        echo "<script>alert('Kerusakan dan solusi tidak boleh kosong!'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO tbl_solusi (id_penyakit, solusi) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $id_penyakit, $solusi);

        if(mysqli_stmt_execute($stmt)){
            $_SESSION['pesan_sukses'] = "Data solusi baru berhasil disimpan.";
            header("Location: ../solusi.php");
            exit;
        } else {
            echo "<script>alert('Gagal menyimpan data. Silakan coba lagi.'); window.history.back();</script>";
        }
    }
}

// Mengambil data admin yang login
$data_admin = mysqli_query($koneksi, "SELECT * FROM user WHERE username='{$_SESSION['username']}'");
$admin = mysqli_fetch_array($data_admin);

// Mengambil daftar penyakit/kerusakan untuk dropdown
$penyakit_query = mysqli_query($koneksi, "SELECT * FROM tbl_penyakit ORDER BY id_penyakit ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah Data Solusi - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
                <div class="sidebar-brand-icon"><i class="fas fa-motorcycle"></i></div>
                <div class="sidebar-brand-text mx-3">
                    <div style="font-size: 1.2rem;">SIPAKAR</div>
                    <div style="font-size: 1.2rem;">MOTOR</div>
                </div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Manajemen Data</div>
            <li class="nav-item"><a class="nav-link" href="../kerusakan.php"><i class="fas fa-fw fa-oil-can"></i><span>Data Kerusakan</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>
            <li class="nav-item"><a class="nav-link" href="../gejala.php"><i class="fas fa-fw fa-list-alt"></i><span>Data Gejala</span></a></li>
            <li class="nav-item"><a class="nav-link" href="../aturan.php"><i class="fas fa-fw fa-cogs"></i><span>Basis Aturan</span></a></li>
            <li class="nav-item"><a class="nav-link" href="../riwayat.php"><i class="fas fa-fw fa-history"></i><span>Riwayat Diagnosa</span></a></li>
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
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Form Tambah Data Solusi</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="POST" action="tambahsolusi.php">
                                <div class="form-group">
                                    <label for="id_penyakit">Untuk Kerusakan</label>
                                    <select class="form-control" id="id_penyakit" name="id_penyakit" required>
                                        <option value="">-- Pilih Kerusakan --</option>
                                        <?php while($p = mysqli_fetch_array($penyakit_query)): ?>
                                            <option value="<?= htmlspecialchars($p['id_penyakit']) ?>"><?= htmlspecialchars($p['id_penyakit']) ?> - <?= htmlspecialchars($p['nama_penyakit']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="solusi">Teks Solusi</label>
                                    <textarea class="form-control" id="solusi" name="solusi" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" name="simpan">Simpan Data</button>
                                <a href="../solusi.php" class="btn btn-secondary">Batal</a>
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
        <!-- Logout modal content omitted for brevity -->
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>
