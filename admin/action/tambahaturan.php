<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location: ../../login.php"); // Sesuaikan path jika perlu
    exit;
}

include '../koneksi.php'; // Sesuaikan path koneksi

// Logika untuk memproses form saat disubmit
if(isset($_POST['simpan'])){
    $id_penyakit = $_POST['id_penyakit'];
    $gejala_terpilih = isset($_POST['gejala']) ? $_POST['gejala'] : [];

    // Validasi: pastikan penyakit dan minimal satu gejala dipilih
    if(empty($id_penyakit) || empty($gejala_terpilih)) {
        // Tampilkan pesan error jika validasi gagal
        echo "<script>alert('Error: Silakan pilih kerusakan dan minimal satu gejala.'); window.history.back();</script>";
    } else {
        // Hapus aturan lama untuk penyakit ini (jika ada) untuk memastikan data bersih
        $sql_delete = "DELETE FROM tbl_basis_aturan WHERE id_penyakit = ?";
        $stmt_delete = mysqli_prepare($koneksi, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "s", $id_penyakit);
        mysqli_stmt_execute($stmt_delete);

        // Siapkan statement untuk insert data baru
        $sql_insert = "INSERT INTO tbl_basis_aturan (id_penyakit, id_gejala) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($koneksi, $sql_insert);

        $sukses = true;
        // Loop dan simpan setiap gejala yang dipilih
        foreach($gejala_terpilih as $id_gejala) {
            mysqli_stmt_bind_param($stmt_insert, "ss", $id_penyakit, $id_gejala);
            if(!mysqli_stmt_execute($stmt_insert)){
                $sukses = false;
                break; // Hentikan jika ada error
            }
        }

        if($sukses) {
            // Jika berhasil, redirect dengan pesan sukses
            $_SESSION['pesan_sukses'] = "Aturan baru berhasil disimpan.";
            header("location: ../aturan.php");
            exit;
        } else {
            // Jika gagal
            echo "<script>alert('Error: Gagal menyimpan aturan baru.'); window.history.back();</script>";
        }
    }
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
    <title>Tambah Aturan Baru - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/baron" type="text/css"/>
    <style>
        .gejala-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem 1.5rem;
        }
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
            <li class="nav-item"><a class="nav-link" href="../gejala.php"><i class="fas fa-fw fa-list-alt"></i><span>Data Gejala</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../aturan.php"><i class="fas fa-fw fa-cogs"></i><span>Basis Aturan</span></a></li>
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
                    <h1 class="h3 mb-4 text-gray-800">Form Tambah Aturan Baru</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Buat Aturan "JIKA [Gejala...] MAKA [Kerusakan]"</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="tambahaturan.php">
                                <div class="form-group">
                                    <label for="id_penyakit" class="font-weight-bold">Langkah 1: Pilih Hasil Kerusakan (MAKA...)</label>
                                    <select name="id_penyakit" id="id_penyakit" class="form-control" required>
                                        <option value="">-- Pilih Kerusakan --</option>
                                        <?php 
                                        // Query untuk mengambil kerusakan yang BELUM punya aturan
                                        $query_penyakit = "SELECT * FROM tbl_penyakit WHERE id_penyakit NOT IN (SELECT DISTINCT id_penyakit FROM tbl_basis_aturan)";
                                        $result_penyakit = mysqli_query($koneksi, $query_penyakit);
                                        while($p = mysqli_fetch_array($result_penyakit)){
                                            echo "<option value='".htmlspecialchars($p['id_penyakit'])."'>[".htmlspecialchars($p['id_penyakit'])."] ".htmlspecialchars($p['nama_penyakit'])."</option>";
                                        }
                                        ?>
                                    </select>
                                    <small class="form-text text-muted">Hanya menampilkan kerusakan yang belum memiliki aturan. Untuk mengubah, gunakan tombol Edit pada halaman Basis Aturan.</small>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="font-weight-bold">Langkah 2: Pilih Gejala-Gejala Pemicunya (JIKA...)</label>
                                    <div class="p-3 border rounded mt-2">
                                        <div class="gejala-grid">
                                            <?php 
                                            $data_gejala = mysqli_query($koneksi, "SELECT * FROM tbl_gejala ORDER BY id_gejala");
                                            while($g = mysqli_fetch_array($data_gejala)){
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gejala[]" value="<?= htmlspecialchars($g['id_gejala']) ?>" id="gejala-<?= htmlspecialchars($g['id_gejala']) ?>">
                                                <label class="form-check-label" for="gejala-<?= htmlspecialchars($g['id_gejala']) ?>">
                                                    [<?= htmlspecialchars($g['id_gejala']) ?>] <?= htmlspecialchars($g['nama_gejala']) ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary" name="simpan">Simpan Aturan</button>
                                    <a href="../aturan.php" class="btn btn-secondary">Batal</a>
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
        </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>