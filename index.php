<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosa Kerusakan Motor</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/baron" type="text/css"/>
    <style>
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
            <button class="navbar-toggler bg-light" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <div class="container">
        <section id="home" class="my-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="img/diagnosa.png" class="img-fluid rounded shadow" alt="Mekanik memperbaiki motor">
                    
                </div>
                <div class="col-md-6">
                    <h1 class="font-weight-bold">Temukan Masalah Pada Motor Anda Dengan Cepat & Akurat</h1>
                    <p class="lead" style="text-align: justify;">Motor Anda bermasalah? Jangan panik. Gunakan sistem pakar kami untuk melakukan diagnosa awal berdasarkan gejala-gejala yang Anda alami. Sistem ini dirancang untuk membantu Anda mengidentifikasi kemungkinan kerusakan sebagai panduan sebelum membawanya ke bengkel.</p>
                    <a href="diagnosa.php" class="btn btn-primary btn-lg mt-3">
                      Mulai Diagnosa Sekarang
                    </a>
                </div>
            </div>
        </section>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>
</body>
</html> 