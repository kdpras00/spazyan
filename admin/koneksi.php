<?php
try {
    $koneksi = mysqli_connect("localhost", "kure8737_kdpras00", "Panggung00@", "kure8737_spazyan");
} catch (mysqli_sql_exception $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// Cek timeout session secara global
$is_admin_dir = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false);
$auth_path = $is_admin_dir ? '../auth_timeout.php' : 'auth_timeout.php';

if (file_exists($auth_path)) {
    include $auth_path;
}
?>