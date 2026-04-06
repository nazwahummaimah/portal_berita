<?php
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

// Ambil ID dari URL
$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM artikel WHERE id_artikel = '$id'");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='kelola_artikel.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .card-form { border-radius: 24px; padding: 40px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.02); background: white; }
        .btn-update { background: #4f46e5; color: white; border-radius: 12px; font-weight: 700; transition: 0.3s; border: none; }
        .btn-update:hover { background: #4338ca; transform: translateY(-2px); color: white; }
        .img-current { border-radius: 16px; object-fit: cover; border: 4px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="index.php?menu=kelola_artikel" class="text-decoration-none text-muted small fw-bold d-block mb-1">
                            <i data-lucide="arrow-left" class="d-inline" style="width:14px"></i> Kembali ke List
                        </a>
                        <h2 class="fw-bold m-0">Edit Berita</h2>
                    </div>
                    <?php if($data['status'] == 'draft'): ?>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Status: Draft</span>
                    <?php else: ?>
                        <span class="badge bg-success px-3 py-2 rounded-pill">Status: Published</span>
                    <?php endif; ?>
                </div>
                
                <div class="card card-form">
                    <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_artikel" value="<?= $data['id_artikel']; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Judul Berita</label>
                            <input type="text" name="judul" class="form-control form-control-lg rounded-3" value="<?= $data['judul']; ?>" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="id_kategori" class="form-select rounded-3" required>
                                    <?php
                                    $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                    while($k = mysqli_fetch_array($kat)) {
                                        $select = ($k['id_kategori'] == $data['id_kategori']) ? "selected" : "";
                                        echo "<option value='$k[id_kategori]' $select>$k[nama_kategori]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Status Publikasi</label>
                                <select name="status" class="form-select rounded-3" required>
                                    <option value="draft" <?= ($data['status'] == 'draft') ? 'selected' : ''; ?>>Simpan sebagai Draft</option>
                                    <option value="publish" <?= ($data['status'] == 'publish') ? 'selected' : ''; ?>>Terbitkan (Publish)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Ganti Gambar</label>
                                <input type="file" name="gambar" class="form-control rounded-3" accept="image/*">
                            </div>
                        </div>

                        <div class="row mb-4 align-items-center bg-light p-3 rounded-4 mx-0">
                            <div class="col-md-3 text-center">
                                <img src="../gambar/<?= $data['gambar']; ?>" width="100%" class="img-current shadow-sm">
                            </div>
                            <div class="col-md-9">
                                <p class="m-0 fw-bold">Gambar Saat Ini</p>
                                <p class="small text-muted m-0">Nama file: <?= $data['gambar']; ?></p>
                                <p class="small text-muted m-0">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Isi Berita</label>
                            <textarea name="isi" class="form-control rounded-4" rows="12" required><?= $data['isi']; ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="update" class="btn btn-update py-3 d-flex align-items-center justify-content-center gap-2">
                                <i data-lucide="save" size="20"></i> Simpan Perubahan Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>