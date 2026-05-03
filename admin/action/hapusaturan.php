<?php
session_start();

// Selalu tambahkan pengecekan login di file aksi
if(!isset($_SESSION['username'])){
    // Jika tidak ada sesi, kembalikan ke halaman login
    header("location: ../../login.php");
    exit;
}

include '../koneksi.php';

// Cek apakah id_penyakit ada di URL dan tidak kosong
if (isset($_GET['id_penyakit']) && !empty($_GET['id_penyakit'])) {
    
    $id_penyakit = $_GET['id_penyakit'];

    // Gunakan prepared statement untuk keamanan
    $sql = "DELETE FROM tbl_basis_aturan WHERE id_penyakit = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    // Bind parameter ke statement
    mysqli_stmt_bind_param($stmt, "s", $id_penyakit);

    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, atur pesan sukses di session
        $_SESSION['pesan_sukses'] = "Aturan berhasil dihapus.";
    } else {
        // Opsional: atur pesan error jika gagal
        $_SESSION['pesan_error'] = "Gagal menghapus aturan.";
    }
    
    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika tidak ada ID, atur pesan error
    $_SESSION['pesan_error'] = "Gagal menghapus, ID aturan tidak valid.";
}

// Tutup koneksi
mysqli_close($koneksi);

// Alihkan kembali ke halaman aturan.php
// Halaman aturan.php akan menampilkan notifikasi dari session
header("Location: ../aturan.php");
exit;

?>