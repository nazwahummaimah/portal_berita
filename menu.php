<?php
// Ambil kategori untuk navigasi
$kategori_nav = mysqli_query($conn, "SELECT * FROM kategori LIMIT 6");
$search = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informash - Portal Berita Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --blue-main: #0056b3; --blue-dark: #003d80; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .top-nav { background: white; border-top: 5px solid var(--blue-main); border-bottom: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 15px 0; }
        .navbar-brand { color: var(--blue-main) !important; font-weight: 800; font-size: 1.6rem; letter-spacing: -1px; }
        .nav-link { color: #444 !important; font-size: 0.9rem; transition: 0.3s; }
        .nav-link:hover { color: var(--blue-main) !important; }
        
        /* Hero Styles (untuk home_isi) */
        .hero-main, .hero-side { position: relative; overflow: hidden; background: #000; border-radius: 4px; }
        .hero-main { height: 420px; } .hero-side { height: 206px; }
        .hero-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.75; transition: 0.5s; }
        .hero-text { position: absolute; bottom: 0; left: 0; padding: 25px; color: white; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.9)); }
        
        /* Element Global */
        .section-title { border-left: 5px solid var(--blue-main); padding-left: 15px; font-weight: 800; text-transform: uppercase; font-size: 1.1rem; margin-bottom: 25px; }
        .pop-item { border-bottom: 1px solid #f1f1f1; padding: 12px 0; display: flex; text-decoration: none; color: #333; align-items: flex-start; }
        .pop-number { color: var(--blue-main); font-weight: 800; font-size: 1.3rem; margin-right: 15px; opacity: 0.3; }
        
        /* Custom Button */
        .btn-login { background-color: var(--blue-main); color: white; border-radius: 20px; padding: 5px 20px; font-weight: bold; border: none; transition: 0.3s; }
        .btn-login:hover { background-color: var(--blue-dark); color: white; transform: scale(1.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg top-nav mb-4">
    <div class="container">
        <a class="navbar-brand me-4" href="index.php?menu=home">INFO<span style="color:#333">RMASH.</span></a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link fw-bold px-3" href="index.php?menu=home">HOME</a></li>
                <?php while($cat = mysqli_fetch_assoc($kategori_nav)): ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="index.php?menu=home&kategori=<?= $cat['id_kategori'] ?>">
                            <?= strtoupper($cat['nama_kategori']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <form action="index.php" method="GET" class="d-flex m-0">
                    <input type="hidden" name="menu" value="home">
                    <div class="input-group input-group-sm">
                        <input type="text" name="keyword" class="form-control" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 20px 0 0 20px; width: 140px; border-secondary-subtle">
                        <button class="btn btn-primary" type="submit" style="border-radius: 0 20px 20px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <a href="admin/login.php" class="btn btn-login shadow-sm">
                    LOGIN
                </a>
            </div>
        </div>
    </div>
</nav>