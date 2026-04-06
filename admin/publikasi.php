<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body style="background:#f8fafc; font-family: 'Plus Jakarta Sans', sans-serif;">

<div class="container py-5">

    <h2 class="mb-4 fw-bold">Portal Berita</h2>

    <div class="row">
    <?php
    $sql = "SELECT artikel.*, kategori.nama_kategori 
            FROM artikel 
            LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
            ORDER BY artikel.id_artikel DESC";

    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) == 0){
        echo "<p class='text-center'>Belum ada berita</p>";
    }

    while($row = mysqli_fetch_assoc($query)) {
    ?>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">

            <!-- GAMBAR -->
            <?php if(!empty($row['gambar'])) { ?>
                <img src="gambar/<?php echo $row['gambar']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
            <?php } else { ?>
                <img src="gambar/default.jpg" class="card-img-top" style="height:200px;">
            <?php } ?>

            <div class="card-body">

                <!-- KATEGORI -->
                <span class="badge bg-primary mb-2">
                    <?php echo $row['nama_kategori'] ?? 'Tidak ada kategori'; ?>
                </span>

                <!-- JUDUL -->
                <h5>
                    <a href="detail_berita.php?id=<?php echo $row['id_artikel']; ?>" style="text-decoration:none; color:black;">
                        <?php echo $row['judul']; ?>
                    </a>
                </h5>

                <!-- ISI SINGKAT -->
                <p>
                    <?php echo substr(strip_tags($row['isi']), 0, 100); ?>...
                </p>

                <!-- TANGGAL + VIEWS -->
                <small class="text-muted">
                    <?php echo date('d M Y', strtotime($row['tanggal'])); ?> | 
                    👁 <?php echo $row['views']; ?>
                </small>

            </div>
        </div>
    </div>

    <?php } ?>
    </div>

</div>

<script>
lucide.createIcons();
</script>

</body>
</html>