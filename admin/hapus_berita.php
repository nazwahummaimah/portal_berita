<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar sebelum datanya dihapus
    $ambil_data = mysqli_query($conn, "SELECT gambar FROM artikel WHERE id_artikel = '$id'");
    $data = mysqli_fetch_array($ambil_data);
    $nama_gambar = $data['gambar'];

    // 2. Hapus file gambar dari folder (img atau gambar)
    // Sesuaikan path ini dengan nama folder kamu (../img/ atau ../gambar/)
    $path = "../img/" . $nama_gambar; 
    
    if (file_exists($path)) {
        unlink($path); // Menghapus file fisik
    }

    // 3. Hapus data dari database
    $hapus = mysqli_query($conn, "DELETE FROM artikel WHERE id_artikel = '$id'");

    if ($hapus) {
        echo "<script>
                alert('Berita berhasil dihapus!');
                window.location='index.php?menu=kelola_artikel';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus berita.');
                window.location='index.php?menu=kelola_artikel';
              </script>";
    }
} else {
    header("Location:index.php?menu=kelola_artikel");
}
?>