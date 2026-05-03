<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location: ../login.php");
    exit;
}

include 'koneksi.php';

// Ambil ID Penyakit dari URL
if(!isset($_GET['id_penyakit'])) {
    header("location: aturan.php");
    exit;
}
$id_penyakit_edit = $_GET['id_penyakit'];

// Logika untuk MEMPROSES form saat disubmit
if(isset($_POST['ubah'])){
    $id_penyakit = $_POST['id_penyakit'];
    $gejala_terpilih = isset($_POST['gejala']) ? $_POST['gejala'] : [];

    if(empty($id_penyakit) || empty($gejala_terpilih)) {
        echo "<script>alert('Error: Silakan pilih minimal satu gejala.'); window.history.back();</script>";
    } else {
        // STRATEGI UPDATE: Hapus semua aturan lama untuk penyakit ini, lalu masukkan yang baru.
        // Ini cara paling aman untuk memastikan data selalu konsisten.
        
        // 1. Hapus aturan lama
        $sql_delete = "DELETE FROM tbl_basis_aturan WHERE id_penyakit = ?";
        $stmt_delete = mysqli_prepare($koneksi, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "s", $id_penyakit);
        mysqli_stmt_execute($stmt_delete);

        // 2. Masukkan aturan baru
        $sql_insert = "INSERT INTO tbl_basis_aturan (id_penyakit, id_gejala) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($koneksi, $sql_insert);

        $sukses = true;
        foreach($gejala_terpilih as $id_gejala) {
            mysqli_stmt_bind_param($stmt_insert, "ss", $id_penyakit, $id_gejala);
            if(!mysqli_stmt_execute($stmt_insert)){
                $sukses = false;
                break;
            }
        }

        if($sukses) {
            $_SESSION['pesan_sukses'] = "Aturan berhasil diperbarui.";
            header("location: aturan.php");
            exit;
        } else {
            echo "<script>alert('Error: Gagal memperbarui aturan.'); window.history.back();</script>";
        }
    }
}

// Mengambil data admin yang login
$data_admin = mysqli_query($koneksi, "SELECT * FROM user WHERE username='{$_SESSION['username']}'");
$admin = mysqli_fetch_array($data_admin);

// --- BAGIAN PENTING UNTUK EDIT ---
// 1. Ambil detail kerusakan yang akan diedit
$query_penyakit = mysqli_query($koneksi, "SELECT * FROM tbl_penyakit WHERE id_penyakit = '$id_penyakit_edit'");
$penyakit = mysqli_fetch_assoc($query_penyakit);

// 2. Ambil semua gejala yang saat ini terhubung dengan kerusakan tersebut
$query_gejala_lama = mysqli_query($koneksi, "SELECT id_gejala FROM tbl_basis_aturan WHERE id_penyakit = '$id_penyakit_edit'");
$gejala_lama = [];
while($row = mysqli_fetch_assoc($query_gejala_lama)) {
    $gejala_lama[] = $row['id_gejala'];
}
// --- AKHIR BAGIAN PENTING ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Aturan - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/baron" type="text/css"/>
    <style>
        .gejala-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem 1.5rem; }
        @media (max-width: 768px) { .gejala-grid { grid-template-columns: 1fr; } }
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
            <li class="nav-item"><a class="nav-link" href="kerusakan.php"><i class="fas fa-fw fa-oil-can"></i><span>Data Kerusakan</span></a></li>
            <li class="nav-item"><a class="nav-link" href="gejala.php"><i class="fas fa-fw fa-list-alt"></i><span>Data Gejala</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="aturan.php"><i class="fas fa-fw fa-cogs"></i><span>Basis Aturan</span></a></li>
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
                    <h1 class="h3 mb-4 text-gray-800">Form Edit Aturan</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Ubah Aturan untuk: <?= htmlspecialchars($penyakit['nama_penyakit']) ?></h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="editaturan.php?id_penyakit=<?= htmlspecialchars($id_penyakit_edit) ?>">
                                <input type="hidden" name="id_penyakit" value="<?= htmlspecialchars($penyakit['id_penyakit']) ?>">

                                <div class="form-group">
                                    <label class="font-weight-bold">Kerusakan (Hasil Diagnosa):</label>
                                    <input type="text" class="form-control" value="[<?= htmlspecialchars($penyakit['id_penyakit']) ?>] <?= htmlspecialchars($penyakit['nama_penyakit']) ?>" readonly>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="font-weight-bold">Pilih Gejala-Gejala Pemicunya (JIKA...)</label>
                                    <div class="p-3 border rounded mt-2">
                                        <div class="gejala-grid">
                                            <?php 
                                            $data_gejala = mysqli_query($koneksi, "SELECT * FROM tbl_gejala ORDER BY id_gejala");
                                            while($g = mysqli_fetch_array($data_gejala)){
                                                // Cek apakah gejala ini harus dicentang
                                                $isChecked = in_array($g['id_gejala'], $gejala_lama) ? 'checked' : '';
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gejala[]" value="<?= htmlspecialchars($g['id_gejala']) ?>" id="gejala-<?= htmlspecialchars($g['id_gejala']) ?>" <?= $isChecked ?>>
                                                <label class="form-check-label" for="gejala-<?= htmlspecialchars($g['id_gejala']) ?>">
                                                    [<?= htmlspecialchars($g['id_gejala']) ?>] <?= htmlspecialchars($g['nama_gejala']) ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary" name="ubah">Simpan Perubahan</button>
                                    <a href="aturan.php" class="btn btn-secondary">Batal</a>
                                </div>
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