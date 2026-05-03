<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location: ../login.php");
    exit;
}

include 'koneksi.php';

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
    <title>Manajemen Basis Aturan - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
        .action-btns {
            display: flex;
            justify-content: center;
            gap: 5px;
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
                    <h1 class="h3 mb-2 text-gray-800">Manajemen Basis Aturan</h1>
                    <p class="mb-4">Halaman ini menampilkan semua aturan "JIKA... MAKA..." yang digunakan oleh sistem. Setiap aturan mengelompokkan gejala-gejala yang mengarah pada satu kesimpulan kerusakan.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="action/tambahaturan.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Tambah Aturan Baru</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kerusakan (Kesimpulan)</th>
                                            <th>Daftar Gejala (Syarat)</th>
                                            <th width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query canggih untuk menggabungkan aturan, penyakit, dan gejala
                                        $query = "SELECT p.id_penyakit, p.nama_penyakit, 
                                                  GROUP_CONCAT(CONCAT('<li>', g.nama_gejala, '</li>') SEPARATOR '') AS daftar_gejala
                                                  FROM tbl_basis_aturan ba
                                                  JOIN tbl_penyakit p ON ba.id_penyakit = p.id_penyakit
                                                  JOIN tbl_gejala g ON ba.id_gejala = g.id_gejala
                                                  GROUP BY p.id_penyakit, p.nama_penyakit
                                                  ORDER BY p.id_penyakit";
                                        $result = mysqli_query($koneksi, $query);
                                        $no = 0;
                                        while ($d = mysqli_fetch_assoc($result)) {
                                            $no++;
                                        ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td><strong>[<?= htmlspecialchars($d['id_penyakit']) ?>]</strong> <?= htmlspecialchars($d['nama_penyakit']) ?></td>
                                            <td><ul class="mb-0 ps-3"><?= $d['daftar_gejala'] ?></ul></td>
                                            <td>
                                                <div class="action-btns">
                                                    <a href="editaturan.php?id_penyakit=<?= $d['id_penyakit'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusModal<?= $d['id_penyakit'] ?>" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="hapusModal<?= $d['id_penyakit'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus aturan untuk kerusakan <strong>"<?= htmlspecialchars($d['nama_penyakit']) ?>"</strong>?</p>
                                                        <p class="text-danger">Tindakan ini akan menghapus semua relasi gejala untuk kerusakan ini.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <a href="action/hapusaturan.php?id_penyakit=<?= $d['id_penyakit'] ?>" class="btn btn-danger">Ya, Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
</body>
</html>