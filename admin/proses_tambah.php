<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    // 1. Tangkap data dari form
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_kategori = $_POST['id_kategori'];
    $isi         = mysqli_real_escape_string($conn, $_POST['isi']);
    
    // TANGKAP STATUS (PENTING!)
    // Jika dari form hidden input atau value tombol submit
    $status      = mysqli_real_escape_string($conn, $_POST['status']); 
    
    $tanggal     = date('Y-m-d H:i:s');
    
    // Ambil ID User dari session
    $id_user     = $_SESSION['id_user']; 

    // 2. Olah Gambar
    $foto       = $_FILES['gambar']['name'];
    $tmp        = $_FILES['gambar']['tmp_name'];
    
    // Beri nama unik agar tidak bentrok jika ada file bernama sama
    $ekstensi   = pathinfo($foto, PATHINFO_EXTENSION);
    $nama_baru  = time() . "_" . rand(100, 999) . "." . $ekstensi; 
    $path       = "../gambar/" . $nama_baru;

    // 3. Eksekusi
    if (move_uploaded_file($tmp, $path)) {
        // QUERY INSERT (Menambahkan kolom 'status')
        $sql = "INSERT INTO artikel (id_kategori, id_user, judul, isi, gambar, tanggal, status, views) 
                VALUES ('$id_kategori', '$id_user', '$judul', '$isi', '$nama_baru', '$tanggal', '$status', 0)";
        
        if (mysqli_query($conn, $sql)) {
            // Pesan alert dinamis sesuai status
            $pesan = ($status == 'publish') ? 'Berita Berhasil Terbit!' : 'Berita Disimpan sebagai Draft!';
            
            echo "<script>
                    alert('$pesan');
                    window.location='kelola_artikel.php';
                  </script>";
        } else {
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        echo "<script>
                alert('Gagal upload gambar! Periksa koneksi atau folder tujuan.');
                window.history.back();
              </script>";
    }
}
?>