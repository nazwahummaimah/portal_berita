<?php
include 'koneksi.php';

$id = $_GET['id'];

// tambah views
mysqli_query($conn, "UPDATE artikel SET views = views + 1 WHERE id_artikel = '$id'");

// ambil data
$query = mysqli_query($conn, "
    SELECT artikel.*, kategori.nama_kategori 
    FROM artikel 
    JOIN kategori ON artikel.id_kategori = kategori.id_kategori
    WHERE artikel.id_artikel = '$id'
");

$data = mysqli_fetch_assoc($query);
?>

<h1><?php echo $data['judul']; ?></h1>
<p>Kategori: <?php echo $data['nama_kategori']; ?></p>
<p><?php echo date('d M Y', strtotime($data['tanggal'])); ?></p>

<img src="img/<?php echo $data['gambar']; ?>" width="400">

<p><?php echo $data['isi']; ?></p>

<p>Views: <?php echo $data['views']; ?></p>