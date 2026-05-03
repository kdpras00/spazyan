<?php
session_start();

if(!isset($_SESSION['status'])){
    header("location:login.php");
    exit;
} 

include "admin/koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosa</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .gejala-list {
            background-color: #f8f9fc;
            border-left: 3px solid #4e73df;
        }
        .navbar .dropdown-menu { 
            min-width: 300px !important; 
            right: -50px !important; 
            left: auto !important; 
        }
        .dropdown-header { 
            white-space: nowrap !important; 
            overflow: visible !important;
            font-size: 0.9rem !important;
        }
        .logo-text {
            font-family: 'BaronNeueRegular', sans-serif;
            line-height: 0.8;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container">
            <div class="navbar-brand d-flex align-items-center" style="cursor: default;">
                <img src="img/iconpakarmotor.png" alt="Logo" width="70" height="70" class="mr-2">
                <div class="logo-text">
                    <div style="font-size: 1.8rem;">SIPAKAR</div>
                    <div style="font-size: 1.8rem;">MOTOR</div>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Center Links -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php" style="color: #ffffff;">Home</a>
                    </li>
                    <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" href="about.php" style="color: #ffffff;">About</a>
                    </li>
                    <?php if(isset($_SESSION['status'])): ?>
                        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'diagnosa.php' ? 'active' : '' ?>">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'diagnosa.php' ? 'active' : '' ?>" href="diagnosa.php" style="color: #ffffff;">Diagnosa</a>
                        </li>
                        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>" href="riwayat.php" style="color: #ffffff;">Riwayat</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Right Buttons / Profile -->
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php if(!isset($_SESSION['status'])): ?>
                        <li class="nav-item mt-2 mt-lg-0" style="margin-right: 10px;">
                            <a href="login.php" class="btn btn-outline-light w-100">Login</a>
                        </li>
                        <li class="nav-item mt-2 mt-lg-0">
                            <a href="register.php" class="btn btn-primary w-100">Register</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #ffffff;">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="min-width: 200px;">
                                <h6 class="dropdown-header">Halo, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?>!</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary text-center">Riwayat Diagnosa Anda</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Tanggal Diagnosa</th>
                                <th>Hasil Kerusakan</th>
                                <th style="width: 15%;">Kecocokan</th>
                                <th style="width: 15%;">Detail Gejala</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $user_id = $_SESSION['user_id'];
                            // Cek apakah kolom persentase ada
                            $chk_pct = mysqli_query($koneksi, "SHOW COLUMNS FROM hasil_diagnosa LIKE 'persentase'");
                            $has_pct = mysqli_num_rows($chk_pct) > 0;
                            $select_pct = $has_pct ? ", hd.persentase" : ", NULL as persentase";

                            $query = "SELECT hd.id_hasil, hd.tanggal, hd.gejala_terpilih, p.nama_penyakit $select_pct
                                      FROM hasil_diagnosa hd
                                      JOIN tbl_penyakit p ON hd.id_penyakit = p.id_penyakit
                                      WHERE hd.id_pas = '$user_id' 
                                      ORDER BY hd.tanggal DESC";
                            $result = mysqli_query($koneksi, $query);
                            $no = 0;

                            if (mysqli_num_rows($result) > 0) {
                                while($data = mysqli_fetch_assoc($result)) {
                                    $no++;
                                    $gejala_ids_string = "'" . str_replace(",", "','", $data['gejala_terpilih']) . "'";
                                    $pct = $data['persentase'];
                                    if ($pct !== null) {
                                        if ($pct >= 80) $bar = 'bg-danger';
                                        elseif ($pct >= 60) $bar = 'bg-warning';
                                        else $bar = 'bg-info';
                                    }
                            ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= date('d F Y, H:i', strtotime($data['tanggal'])) ?></td>
                                <td><strong><?= htmlspecialchars($data['nama_penyakit']) ?></strong></td>
                                <td class="text-center">
                                    <?php if ($pct !== null): ?>
                                    <div style="font-size:0.85rem;font-weight:700;margin-bottom:3px;"><?= $pct ?>%</div>
                                    <div class="progress" style="height:8px;border-radius:50px;">
                                        <div class="progress-bar <?= $bar ?>" style="width:<?= $pct ?>%"></div>
                                    </div>
                                    <?php else: echo '<span class="text-muted">-</span>'; endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" data-toggle="collapse" data-target="#detail-<?= $data['id_hasil'] ?>">
                                        <i class="fa fa-eye mr-2"></i> Lihat
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="detail-<?= $data['id_hasil'] ?>">
                                <td colspan="5">
                                    <div class="p-3 gejala-list">
                                        <h6 class="font-weight-bold">Gejala yang dipilih:</h6>
                                        <ul>
                                            <?php
                                            // Query untuk mengambil nama gejala berdasarkan ID
                                            $sql_gejala = "SELECT nama_gejala FROM tbl_gejala WHERE id_gejala IN ($gejala_ids_string)";
                                            $result_gejala = mysqli_query($koneksi, $sql_gejala);
                                            while($gejala = mysqli_fetch_assoc($result_gejala)) {
                                                echo "<li>" . htmlspecialchars($gejala['nama_gejala']) . "</li>";
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } // akhir while
                            } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                    <h5>Belum ada riwayat diagnosa.</h5>
                                    <p class="small">Silakan lakukan diagnosa terlebih dahulu untuk melihat riwayat di sini.</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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