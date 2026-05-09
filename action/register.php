<?php
$username = $_POST['username'] ?? '';
$jeniskelamin = $_POST['jeniskelamin'] ?? '';
$email = $_POST['email'] ?? '';
$passwordRaw = $_POST['password'] ?? '';
$role = 2;

if(empty($username) || empty($email) || empty($passwordRaw)) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Gagal',
                text: 'Data tidak boleh kosong',
                icon: 'error',
                confirmButtonColor: '#4e73df'
            }).then(() => {
                window.location.href='../register.php';
            });
        }, 100);
    </script>";
    exit;
}

$password = md5($passwordRaw);

include '../admin/koneksi.php';

$simpan = mysqli_query($koneksi, "INSERT into user(username,jeniskelamin, email, password, role) VALUES('$username','$jeniskelamin','$email','$password','$role')");
if($simpan){
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Berhasil',
                text: 'Berhasil Register',
                icon: 'success',
                confirmButtonColor: '#4e73df'
            }).then(() => {
                window.location.href='../login.php';
            });
        }, 100);
    </script>";
}else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Gagal',
                text: 'Gagal Register',
                icon: 'error',
                confirmButtonColor: '#4e73df'
            }).then(() => {
                window.location.href='../register.php';
            });
        }, 100);
    </script>";
}
?>