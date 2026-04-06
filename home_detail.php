<?php
// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: index.php");
    exit();
}

// 2. Update jumlah Views
mysqli_query($conn, "UPDATE artikel SET views = views + 1 WHERE id_artikel = '$id'");

// 3. Ambil Data Berita Lengkap
$sql = "SELECT a.*, k.nama_kategori, u.username 
        FROM artikel a 
        LEFT JOIN kategori k ON a.id_kategori = k.id_kategori 
        LEFT JOIN users u ON a.id_user = u.id_user 
        WHERE a.id_artikel = '$id'";
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

// 4. Ambil Berita Populer untuk Sidebar
$populer_q = mysqli_query($conn, "SELECT id_artikel, judul, gambar, views 
                                  FROM artikel 
                                  WHERE status = 'publish' AND id_artikel != '$id' 
                                  ORDER BY views DESC LIMIT 5");

// 5. Ambil Kategori untuk Sidebar
$kategori_sidebar = mysqli_query($conn, "SELECT * FROM kategori");

// Proteksi jika ID tidak ditemukan
if (!$data) {
    echo "<script>alert('Berita tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

// Share URL
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$share_title = urlencode($data['judul']);
$share_url = urlencode($actual_link);
?>

<style>
/* Sidebar & Widget */
.sidebar-widget { background: white; padding: 25px; border-radius: 8px; border: 1px solid #f0f0f0; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
.widget-title { font-weight: 800; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.widget-title::before { content: ''; display: inline-block; width: 4px; height: 18px; background: #2c4a91; border-radius: 2px; }

/* Banner Redaksi */
.banner-redaksi { background: #2c4a91; color: white; padding: 30px 25px; border-radius: 8px; margin-bottom: 25px; }
.banner-redaksi h5 { font-weight: 700; margin-bottom: 10px; font-size: 1.2rem; }
.banner-redaksi p { font-size: 0.85rem; opacity: 0.9; line-height: 1.5; margin-bottom: 20px; }
.btn-hubungi { background: white; color: #2c4a91; font-weight: 700; border: none; width: 100%; padding: 12px; border-radius: 6px; text-decoration: none; display: inline-block; text-align: center; font-size: 0.9rem; transition: 0.3s; }
.btn-hubungi:hover { background: #f0f0f0; color: #1a3366; }

/* Kategori */
.cat-wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
.cat-pill { padding: 6px 18px; border: 1px solid #eee; border-radius: 50px; color: #666; text-decoration: none; font-size: 0.85rem; transition: 0.3s; background: #fff; }
.cat-pill:hover { background: #f8f9fa; border-color: #ccc; color: #333; transform: translateY(-1px); }

/* Detail News Styling */
.news-content { background: white; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0; }
.news-title { font-weight: 800; font-size: 2.5rem; line-height: 1.2; margin-bottom: 20px; color: #111; }
</style>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Article Section -->
        <div class="col-lg-8">
            <div class="news-content shadow-sm">
                <span class="text-primary fw-bold small text-uppercase mb-2 d-block"><?= $data['nama_kategori'] ?></span>
                <h1 class="news-title"><?= $data['judul'] ?></h1>
                
                <div class="d-flex gap-3 mb-4 text-muted small border-bottom pb-3">
                    <span><i class="fas fa-user-circle me-1"></i> <?= $data['username'] ?></span>
                    <span><i class="fas fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($data['tanggal'])) ?></span>
                    <span><i class="fas fa-eye me-1"></i> <?= number_format($data['views']) ?> views</span>
                </div>

                <img src="gambar/<?= $data['gambar'] ?>" class="w-100 rounded mb-4 shadow-sm" alt="Feature Image">

                <div class="article-body" style="line-height: 1.8; font-size: 1.1rem; text-align: justify; color: #333;">
                    <?= nl2br($data['isi']) ?>
                </div>

                <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                    <a href="index.php?menu=home" class="btn btn-outline-primary rounded-pill px-4 fw-bold small">BERITA LAINNYA</a>
                    <div class="share-box">
                        <span class="small fw-bold me-2 text-muted">BAGIKAN:</span>
                        <a href="https://api.whatsapp.com/send?text=<?= $share_title ?>%20-<?= $share_url ?>" target="_blank" class="text-success me-2"><i class="fab fa-whatsapp fa-lg"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>" target="_blank" class="text-primary"><i class="fab fa-facebook fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <div class="sidebar-widget shadow-sm">
                <h5 class="widget-title">Berita Populer</h5>
                <?php while($pop = mysqli_fetch_assoc($populer_q)): ?>
                <a href="index.php?menu=detail&id=<?= $pop['id_artikel'] ?>" class="d-flex align-items-center text-decoration-none mb-3 group">
                    <img src="gambar/<?= $pop['gambar'] ?>" style="width: 70px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3">
                    <h6 class="small fw-bold text-dark mb-0 line-clamp-2"><?= $pop['judul'] ?></h6>
                </a>
                <?php endwhile; ?>
            </div>

            <div class="banner-redaksi shadow-sm">
                <h5>Punya Info Menarik?</h5>
                <p>Jadilah kontributor dan bagikan informasi teknologi terbaru di Informash.</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="btn-hubungi">HUBUNGI REDAKSI</a>
            </div>

        </div>
    </div>
</div>