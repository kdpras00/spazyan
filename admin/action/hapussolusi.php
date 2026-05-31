<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location: ../../login.php");
    exit;
}

include '../koneksi.php';

if(isset($_GET['id_solusi'])){
    $id = $_GET['id_solusi'];
    
    // Gunakan prepared statement untuk keamanan
    $sql = "DELETE FROM tbl_solusi WHERE id_solusi = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if(mysqli_stmt_execute($stmt)){
        $_SESSION['pesan_sukses'] = "Data solusi berhasil dihapus.";
        header("Location: ../solusi.php");
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='../solusi.php';</script>";
    }
} else {
    header("Location: ../solusi.php");
}
?>
