<?php
$koneksi = mysqli_connect ("127.0.0.1","root","","spazyan");

if(mysqli_connect_error()){
    echo "Koneksi Database Gagal : .mysqli_connect_error()";
}

// Cek timeout session secara global
$is_admin_dir = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false);
$auth_path = $is_admin_dir ? '../auth_timeout.php' : 'auth_timeout.php';

if (file_exists($auth_path)) {
    include $auth_path;
}

?>