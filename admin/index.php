<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek login: Memastikan session username tersedia
if (isset($_SESSION['username'])) {
    
    // Menangkap parameter menu dari URL
    $menu = $_GET['menu'] ?? '';
    
    // Memanggil header dan layout utama (Sidebar/Navbar)
    // Pastikan di dalam dashboard.php atau header.php sudah ada CSS & Font Jakarta Sans
    include "dashboard.php"; 

    // Bagian Footer diletakkan di sini agar muncul setelah konten dashboard
    include "footer.php";

} else {
    // Jika belum login, arahkan ke halaman login
    include "login.php";
}
?>