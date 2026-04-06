<?php
// Koneksi database (sesuaikan jika nama filenya berbeda)
include 'koneksi.php';

// --- LOGIKA PENCARIAN & FILTER ---
$keyword = (isset($_GET['keyword'])) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$filter_kat = (isset($_GET['filter_kat'])) ? mysqli_real_escape_string($conn, $_GET['filter_kat']) : '';
$per_halaman = (isset($_GET['per_halaman'])) ? (int)$_GET['per_halaman'] : 5;

$where_clause = "WHERE 1=1";
if ($keyword != '') {
    $where_clause .= " AND artikel.judul LIKE '%$keyword%'";
}
if ($filter_kat != '') {
    $where_clause .= " AND artikel.id_kategori = '$filter_kat'";
}

// --- LOGIKA PAGINATION ---
$sql_total = "SELECT artikel.* FROM artikel $where_clause";
$hasil_total = mysqli_query($conn, $sql_total);
$jumlahData = mysqli_num_rows($hasil_total);
$jumlahHalaman = ceil($jumlahData / $per_halaman);
$halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
$awalData = ($per_halaman * $halamanAktif) - $per_halaman;

$dataMulai = ($jumlahData > 0) ? $awalData + 1 : 0;
$dataSampai = ($awalData + $per_halaman > $jumlahData) ? $jumlahData : $awalData + $per_halaman;
?>

<style>
    .card-table { border: none; border-radius: 24px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.02); overflow: hidden; }
    .img-preview { width: 80px; height: 50px; object-fit: cover; border-radius: 10px; }
    .btn-add { background: #4f46e5; color: white; border-radius: 12px; font-weight: 700; text-decoration: none; transition: 0.3s; }
    .btn-add:hover { background: #4338ca; transform: translateY(-2px); color: white; }
    .pagination .page-link { border: none; color: #64748b; font-weight: 600; border-radius: 10px; margin: 0 4px; padding: 8px 16px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .pagination .page-item.active .page-link { background-color: #4f46e5 !important; color: white !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
    .info-pill { background: white; padding: 8px 20px; border-radius: 50px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); font-size: 0.85rem; color: #64748b; }
    .badge-publish { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-draft { background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <a href="index.php" class="text-decoration-none text-muted small fw-bold mb-2 d-block">
                <i data-lucide="arrow-left" style="width:14px"></i> Kembali ke Dashboard
            </a>
            <h2 class="fw-bold m-0 text-dark">Manajemen Berita</h2>
        </div>
        <a href="index.php?menu=tambah_berita" class="btn btn-add px-4 py-2 d-flex align-items-center gap-2">
            <i data-lucide="plus-circle" size="18"></i> Tambah Berita
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
        <form action="index.php" method="GET" class="row g-3 align-items-center">
            <input type="hidden" name="menu" value="kelola_artikel">
            <div class="col-md-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-bold">Show</span>
                    <select name="per_halaman" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                        <option value="5" <?= ($per_halaman == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?= ($per_halaman == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?= ($per_halaman == 20) ? 'selected' : ''; ?>>20</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" size="16"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0" placeholder="Cari judul..." value="<?= $keyword; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="filter_kat" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <?php
                    $ambil_kategori = mysqli_query($conn, "SELECT * FROM kategori");
                    while($k = mysqli_fetch_array($ambil_kategori)) {
                        $selected = ($filter_kat == $k['id_kategori']) ? 'selected' : '';
                        echo "<option value='$k[id_kategori]' $selected>$k[nama_kategori]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold rounded-3">Terapkan</button>
            </div>
        </form>
    </div>

    <div class="card card-table">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Gambar</th>
                        <th>Judul Berita</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT artikel.*, kategori.nama_kategori 
                            FROM artikel 
                            JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                            $where_clause
                            ORDER BY artikel.id_artikel DESC
                            LIMIT $awalData, $per_halaman";
                    $query = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($query) == 0) {
                        echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Data tidak ditemukan.</td></tr>";
                    }

                    while($row = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td class="ps-4"><img src="../gambar/<?php echo $row['gambar']; ?>" class="img-preview shadow-sm"></td>
                        <td>
                            <div class="fw-bold text-dark" style="max-width: 300px;"><?php echo $row['judul']; ?></div>
                            <span class="text-muted small">Views: <?php echo number_format($row['views']); ?></span>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"><?php echo $row['nama_kategori']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'publish'): ?>
                                <span class="badge badge-publish rounded-pill px-3 py-2">Terbit</span>
                            <?php else: ?>
                                <span class="badge badge-draft rounded-pill px-3 py-2">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?menu=edit_berita&id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-warning rounded-3 px-3 fw-bold">Edit</a>
                                <a href="hapus_berita.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-danger rounded-3 px-3 fw-bold" onclick="return confirm('Hapus berita ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <span class="info-pill">
                Menampilkan <strong><?= $dataMulai; ?></strong> - <strong><?= $dataSampai; ?></strong> dari <strong><?= $jumlahData; ?></strong> berita
            </span>
        </div>
        <div class="col-md-6">
            <nav>
                <ul class="pagination justify-content-center justify-content-md-end mb-0">
                    <li class="page-item <?= ($halamanAktif <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="index.php?menu=kelola_artikel&halaman=<?= $halamanAktif - 1; ?>&keyword=<?= $keyword; ?>&filter_kat=<?= $filter_kat; ?>&per_halaman=<?= $per_halaman; ?>">
                            <i data-lucide="chevron-left" class="d-inline" style="width:16px"></i> Prev
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                        <li class="page-item <?= ($i == $halamanAktif) ? 'active' : ''; ?>">
                            <a class="page-link shadow-sm" href="index.php?menu=kelola_artikel&halaman=<?= $i; ?>&keyword=<?= $keyword; ?>&filter_kat=<?= $filter_kat; ?>&per_halaman=<?= $per_halaman; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($halamanAktif >= $jumlahHalaman || $jumlahData == 0) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="index.php?menu=kelola_artikel&halaman=<?= $halamanAktif + 1; ?>&keyword=<?= $keyword; ?>&filter_kat=<?= $filter_kat; ?>&per_halaman=<?= $per_halaman; ?>">
                            Next <i data-lucide="chevron-right" class="d-inline" style="width:16px"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>