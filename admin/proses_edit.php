<?php
session_start();
include 'koneksi.php';

if (isset($_POST['update'])) {
    $id      = $_POST['id_artikel'];
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi     = mysqli_real_escape_string($conn, $_POST['isi']);
    $id_kat  = $_POST['id_kategori'];
    $status  = $_POST['status']; // INI KUNCINYA: Menangkap status baru (draft/publish)

    $nama_file = $_FILES['gambar']['name'];
    $tmp_file  = $_FILES['gambar']['tmp_name'];

    if ($nama_file != "") {
        // JIKA GANTI GAMBAR
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));

        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            move_uploaded_file($tmp_file, '../gambar/' . $nama_file);
            
            // Query Update termasuk kolom status dan gambar baru
            $query = "UPDATE artikel SET 
                        id_kategori = '$id_kat', 
                        judul = '$judul', 
                        isi = '$isi', 
                        gambar = '$nama_file',
                        status = '$status' 
                      WHERE id_artikel = '$id'";
        } else {
            echo "<script>alert('Ekstensi tidak didukung!'); window.location='edit_berita.php?id=$id';</script>";
            exit();
        }
    } else {
        // JIKA TIDAK GANTI GAMBAR
        // Query Update tetap menyertakan update kolom status
        $query = "UPDATE artikel SET 
                    id_kategori = '$id_kat', 
                    judul = '$judul', 
                    isi = '$isi',
                    status = '$status' 
                  WHERE id_artikel = '$id'";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Berita berhasil diperbarui!'); window.location='index.php?menu=kelola_artikel';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui database!'); window.location='edit_berita.php?id=$id';</script>";
    }
}
?>