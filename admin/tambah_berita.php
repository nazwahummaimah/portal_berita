<?php
include 'koneksi.php';

// Pastikan session sudah dimulai di index.php, jika file ini dipanggil mandiri:
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

// PROSES SIMPAN DATA
if (isset($_POST['simpan'])) {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi      = mysqli_real_escape_string($conn, $_POST['isi']);
    $id_kat   = $_POST['id_kategori'];
    
    // Menangkap status langsung dari value tombol simpan yang diklik
    $status   = mysqli_real_escape_string($conn, $_POST['simpan']); 
    $tanggal  = date('Y-m-d');
    
    // Kelola Gambar
    $nama_file = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));

    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        if ($ukuran_file < 2000000) { // Max 2MB
            move_uploaded_file($tmp_file, '../gambar/' . $nama_file);
            
            // Query Insert
            $query = "INSERT INTO artikel (id_kategori, judul, isi, gambar, tanggal, status, views) 
                      VALUES ('$id_kat', '$judul', '$isi', '$nama_file', '$tanggal', '$status', 0)";
            
            if (mysqli_query($conn, $query)) {
                $pesan = ($status == 'draft') ? 'disimpan sebagai Draft' : 'berhasil diterbitkan';
                // PERBAIKAN DI SINI: Mengarahkan kembali ke index.php agar struktur dashboard tetap terjaga
                echo "<script>alert('Berita $pesan!'); window.location='index.php?menu=kelola_artikel';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.');</script>";
        }
    } else {
        echo "<script>alert('Ekstensi file tidak didukung! Gunakan JPG, JPEG, atau PNG.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .btn-publish { background: #4f46e5; color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; }
        .btn-publish:hover { background: #4338ca; transform: translateY(-2px); color: white; }
        .btn-draft { background: #f1f5f9; color: #64748b; border-radius: 10px; font-weight: 600; border: 1px solid #e2e8f0; transition: 0.3s; }
        .btn-draft:hover { background: #e2e8f0; color: #1e293b; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Tulis Berita Baru</h3>
                    <p class="text-muted small m-0">Buat konten informatif untuk pembaca setia Anda.</p>
                </div>
                <a href="index.php?menu=kelola_artikel" class="btn btn-light rounded-pill px-4 shadow-sm text-muted small fw-bold">Batal</a>
            </div>

            <div class="card card-form p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="judul" class="form-control rounded-3 py-2" placeholder="Masukkan judul yang menarik..." required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="id_kategori" class="form-select rounded-3 py-2" required>
                                <option value="">Pilih Kategori</option>
                                <?php
                                $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                while($k = mysqli_fetch_array($kat)) {
                                    echo "<option value='$k[id_kategori]'>$k[nama_kategori]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gambar Cover</label>
                            <input type="file" name="gambar" class="form-control rounded-3" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Isi Berita</label>
                        <textarea name="isi" class="form-control rounded-3" rows="10" placeholder="Tuliskan isi berita secara lengkap..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <button type="submit" name="simpan" value="draft" class="btn btn-draft px-4 py-2 d-flex align-items-center gap-2">
                            <i data-lucide="archive" size="18"></i> Simpan Draft
                        </button>
                        <button type="submit" name="simpan" value="publish" class="btn btn-publish px-4 py-2 d-flex align-items-center gap-2">
                            <i data-lucide="send" size="18"></i> Terbitkan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>