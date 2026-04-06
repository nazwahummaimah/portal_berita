<?php
include 'admin/koneksi.php';

// Logika Navigasi
$menu = isset($_GET['menu']) ? $_GET['menu'] : 'home';

// 1. Bagian Atas
include 'menu.php';

// 2. Bagian Tengah (Konten Dinamis)
if ($menu == 'detail') {
    include 'home_detail.php';
} else {
    include 'home_isi.php';
}

// 3. Bagian Bawah
include 'footer.php';
?>