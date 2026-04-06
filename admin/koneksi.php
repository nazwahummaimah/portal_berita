<?php 
$conn = mysqli_connect("localhost", "root", "", "dbportal_berita");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>