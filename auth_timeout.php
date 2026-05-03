<?php
// Cek apakah session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Update waktu aktivitas terakhir
$_SESSION['last_activity'] = time();

// Daftar halaman yang dikecualikan (tidak perlu cek timeout)
$current_page = basename($_SERVER['PHP_SELF']);
$excluded_pages = ['login.php', 'proseslogin.php', 'register.php'];

if (in_array($current_page, $excluded_pages)) {
    return;
}

// Set waktu timeout (30 menit = 1800 detik)
$timeout_duration = 1800;

// Cek apakah ada aktivitas terakhir
if (isset($_SESSION['last_activity'])) {
    // Hitung durasi tidak aktif
    $elapsed_time = time() - $_SESSION['last_activity'];
    
    if ($elapsed_time > $timeout_duration) {
        // Sesi kadaluarsa, hapus semua session
        session_unset();
        session_destroy();
        
        // Redirect ke halaman login dengan pesan timeout
        $login_url = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '../login.php' : 'login.php';
        
        header("Location: " . $login_url . "?msg=timeout");
        exit();
    }
}

// Update waktu aktivitas terakhir (pindahkan ke atas agar selalu terupdate jika aktif)
$_SESSION['last_activity'] = time();
?>
