<?php
try {
    $koneksi = mysqli_connect("127.0.0.1", "root", "", "spazyan");
    if ($koneksi) {
        mysqli_query($koneksi, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    }
} catch (mysqli_sql_exception $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

$is_admin_dir = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false);
$auth_path = $is_admin_dir ? '../auth_timeout.php' : 'auth_timeout.php';

if (file_exists($auth_path)) {
    include $auth_path;
}
?>