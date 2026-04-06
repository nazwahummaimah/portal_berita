<?php
// AMBIL DATA STATISTIK (Wajib ada supaya angka tidak nol)
$total_draft = mysqli_num_rows(mysqli_query($conn, "SELECT id_artikel FROM artikel WHERE status = 'draft'"));
$total_publish = mysqli_num_rows(mysqli_query($conn, "SELECT id_artikel FROM artikel WHERE status = 'publish'"));
$total_kategori = mysqli_num_rows(mysqli_query($conn, "SELECT id_kategori FROM kategori"));
$data_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(views) AS total_views FROM artikel"));
$total_views = $data_views['total_views'] ?? 0;
?>

<div class="container py-5">
    <div class="mb-5 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-bold m-0 text-dark">Ringkasan Statistik</h2>
            <p class="text-muted m-0">Halo, <b><?= $_SESSION['username'] ?></b>! Berikut update portal beritamu.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success"><i data-lucide="check-circle"></i></div>
                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.65rem;">LIVE</span>
                </div>
                <div class="stat-title">Terbit</div>
                <div class="stat-value"><?= $total_publish ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning"><i data-lucide="file-edit"></i></div>
                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size: 0.65rem;">DRAFT</span>
                </div>
                <div class="stat-title">Draft</div>
                <div class="stat-value"><?= $total_draft ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary"><i data-lucide="layers"></i></div>
                </div>
                <div class="stat-title">Kategori</div>
                <div class="stat-value"><?= $total_kategori ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-info bg-opacity-10 text-info"><i data-lucide="eye"></i></div>
                </div>
                <div class="stat-title">Total Views</div>
                <div class="stat-value"><?= number_format($total_views) ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">Akses Cepat</h5>
            <div class="d-flex flex-column gap-3">
                <a href="index.php?menu=tambah_berita" class="btn-quick btn-quick-primary text-decoration-none text-white">
                    <i data-lucide="plus-circle"></i> Buat Berita Baru
                </a>
                <a href="../index.php" target="_blank" class="btn-quick btn-quick-success text-white text-decoration-none">
                    <i data-lucide="external-link"></i> Lihat Website
                </a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0">Aktivitas Terakhir</h5>
                <a href="index.php?menu=kelola_artikel" class="btn btn-sm btn-link text-decoration-none small fw-bold">Lihat Semua</a>
            </div>
            <div class="card-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-center">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_terbaru = "SELECT judul, tanggal, views, status FROM artikel ORDER BY id_artikel DESC LIMIT 5";
                            $query_terbaru = mysqli_query($conn, $sql_terbaru);
                            while ($row = mysqli_fetch_assoc($query_terbaru)) {
                                $status_class = ($row['status'] == 'publish') ? 'bg-success text-success' : 'bg-warning text-warning';
                            ?>
                            <tr>
                                <td><div class="fw-bold text-dark text-truncate" style="max-width: 250px;"><?= $row['judul']; ?></div></td>
                                <td>
                                    <span class="badge <?= $status_class ?> bg-opacity-10 rounded-pill px-3 py-2 small" style="font-size: 0.7rem;">
                                        <?= strtoupper($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-muted small"><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                <td class="text-center fw-bold text-muted"><?= number_format($row['views']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>