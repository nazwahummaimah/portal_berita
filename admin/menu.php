<?php
// Menentukan menu yang dipilih
if (isset($_GET['menu'])) {
    $menu = $_GET['menu'];
} else {
    $menu = "";
}

// Logika include halaman
if ($menu == "kelola_artikel") {
    include "kelola_artikel.php";
} 
else if ($menu == "tambah_berita") {
    include "tambah_berita.php";
} 
else if ($menu == "edit_berita") {
    include "edit_berita.php";
} 
else {
    // Halaman default jika menu kosong (Ringkasan Statistik)
    include "home.php";
}
?>