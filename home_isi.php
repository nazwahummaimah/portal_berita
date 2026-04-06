<?php
$filter_kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$search = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

// Base Query
$where_clause = "WHERE a.status = 'publish'";
if (!empty($search)) $where_clause .= " AND (a.judul LIKE '%$search%' OR a.isi LIKE '%$search%')";
if (!empty($filter_kategori)) $where_clause .= " AND a.id_kategori = '$filter_kategori'";

// 1. Ambil Headline
$headline_q = mysqli_query($conn, "SELECT a.*, k.nama_kategori FROM artikel a JOIN kategori k ON a.id_kategori = k.id_kategori $where_clause ORDER BY a.tanggal DESC, a.id_artikel DESC LIMIT 1");
$headline = mysqli_fetch_assoc($headline_q);

// 2. Ambil Berita Samping Headline
$side_where = $where_clause;
if ($headline) { $side_where .= " AND a.id_artikel != '".$headline['id_artikel']."'"; }
$side_q = mysqli_query($conn, "SELECT a.*, k.nama_kategori FROM artikel a JOIN kategori k ON a.id_kategori = k.id_kategori $side_where ORDER BY a.tanggal DESC LIMIT 2");

// 3. Ambil Berita Terbaru (Daftar di bawah)
$offset = (empty($search) && empty($filter_kategori)) ? "OFFSET 3" : "OFFSET 0"; 
$latest_limit = (empty($search) && empty($filter_kategori)) ? "LIMIT 4" : "LIMIT 12";
$latest_q = mysqli_query($conn, "SELECT a.*, k.nama_kategori FROM artikel a JOIN kategori k ON a.id_kategori = k.id_kategori $where_clause ORDER BY a.tanggal DESC $latest_limit $offset");

// 4. Berita Populer
$populer_q = mysqli_query($conn, "SELECT id_artikel, judul FROM artikel WHERE status = 'publish' ORDER BY views DESC LIMIT 5");
?>

<style>
    .news-card { border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.05); transition: 0.3s; border-radius: 8px; overflow: hidden; }
    .news-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    .card-img-top { height: 200px; object-fit: cover; }
    .hover-blue:hover { color: var(--blue-main) !important; }
    .section-title { border-left: 5px solid var(--blue-main); padding-left: 15px; font-weight: 800; text-transform: uppercase; font-size: 1.1rem; margin-bottom: 25px; color: #333; }
    .pop-number { color: var(--blue-main); font-weight: 800; font-size: 1.3rem; opacity: 0.3; }
    
    /* Style Banner CTA baru */
    .cta-banner { 
        background: linear-gradient(45deg, #2c4a91, #0056b3); 
        color: white; 
        padding: 30px 20px; 
        border-radius: 12px; 
        text-align: center; 
        box-shadow: 0 10px 20px rgba(44, 74, 145, 0.2);
    }
</style>

<div class="container">
    <?php if($headline): ?>
    <div class="row g-2 mb-5">
        <div class="col-md-8">
            <div class="hero-main shadow-sm">
                <a href="index.php?menu=detail&id=<?= $headline['id_artikel'] ?>">
                    <img src="gambar/<?= $headline['gambar'] ?>" class="hero-img">
                </a>
                <div class="hero-text">
                    <span class="badge bg-primary mb-2 text-uppercase"><?= $headline['nama_kategori'] ?></span>
                    <h2 class="fw-bold">
                        <a href="index.php?menu=detail&id=<?= $headline['id_artikel'] ?>" class="text-white text-decoration-none"><?= $headline['judul'] ?></a>
                    </h2>
                    <a href="index.php?menu=detail&id=<?= $headline['id_artikel'] ?>" class="text-white small fw-bold text-decoration-none">BACA SELENGKAPNYA <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php while($s = mysqli_fetch_assoc($side_q)): ?>
            <div class="hero-side mb-2 shadow-sm">
                <a href="index.php?menu=detail&id=<?= $s['id_artikel'] ?>">
                    <img src="gambar/<?= $s['gambar'] ?>" class="hero-img">
                </a>
                <div class="hero-text p-3">
                    <h6 class="fw-bold m-0">
                        <a href="index.php?menu=detail&id=<?= $s['id_artikel'] ?>" class="text-white text-decoration-none"><?= $s['judul'] ?></a>
                    </h6>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <h5 class="section-title">Berita Terbaru</h5>
            <div class="row g-4">
                <?php if(mysqli_num_rows($latest_q) > 0): ?>
                    <?php while($l = mysqli_fetch_assoc($latest_q)): ?>
                    <div class="col-md-6">
                        <div class="card news-card h-100">
                            <a href="index.php?menu=detail&id=<?= $l['id_artikel'] ?>">
                                <img src="gambar/<?= $l['gambar'] ?>" class="card-img-top" alt="Thumbnail">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <span class="text-primary small fw-bold text-uppercase mb-2"><?= $l['nama_kategori'] ?></span>
                                <h5 class="fw-bold mb-3">
                                    <a href="index.php?menu=detail&id=<?= $l['id_artikel'] ?>" class="text-dark text-decoration-none hover-blue">
                                        <?= $l['judul'] ?>
                                    </a>
                                </h5>
                                <div class="mt-auto">
                                    <p class="text-muted small mb-3"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($l['tanggal'])) ?></p>
                                    <a href="index.php?menu=detail&id=<?= $l['id_artikel'] ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-bold">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada berita lainnya untuk ditampilkan.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white p-4 shadow-sm rounded-1 mb-4 border">
                <h5 class="section-title mb-4">Populer Pekan Ini</h5>
                <?php $no=1; while($p = mysqli_fetch_assoc($populer_q)): ?>
                <a href="index.php?menu=detail&id=<?= $p['id_artikel'] ?>" class="pop-item d-flex align-items-start text-decoration-none mb-3">
                    <span class="pop-number me-3"><?= $no++ ?></span>
                    <span class="fw-bold small text-dark hover-blue"><?= $p['judul'] ?></span>
                </a>
                <?php endwhile; ?>
            </div>

            <div class="cta-banner mb-4">
                <i class="fas fa-paper-plane fa-3x mb-3 opacity-50"></i>
                <h5 class="fw-bold">Punya Info Menarik?</h5>
                <p class="small opacity-75 mb-4">Kirimkan tulisan atau berita seputar teknologi di sekitarmu.</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-light btn-sm fw-bold w-100 text-primary py-2">HUBUNGI REDAKSI</a>
            </div>

        </div>
    </div>
</div>