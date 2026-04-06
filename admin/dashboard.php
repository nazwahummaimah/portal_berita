<?php
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

// --- MENGAMBIL DATA STATISTIK ---
$total_artikel = mysqli_num_rows(mysqli_query($conn, "SELECT id_artikel FROM artikel"));
$total_draft = mysqli_num_rows(mysqli_query($conn, "SELECT id_artikel FROM artikel WHERE status = 'draft'"));
$total_publish = mysqli_num_rows(mysqli_query($conn, "SELECT id_artikel FROM artikel WHERE status = 'publish'"));
$total_kategori = mysqli_num_rows(mysqli_query($conn, "SELECT id_kategori FROM kategori"));
$data_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(views) AS total_views FROM artikel WHERE status = 'publish'"));
$total_views = $data_views['total_views'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PORTAL ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .navbar-custom { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 0.8rem 0; }
        
        /* Logout Button Eye-Catching */
        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1.5px solid rgba(239, 68, 68, 0.2);
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: white;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        .card-stat { border: none; border-radius: 20px; background: white; padding: 24px; transition: 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.02); height: 100%; }
        .card-stat:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .stat-title { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin-top: 4px; }
        .btn-quick { border-radius: 16px; padding: 18px; font-weight: 700; display: flex; align-items: center; gap: 12px; transition: 0.3s; border: none; width: 100%; }
        .btn-quick-primary { background-color: #4f46e5; color: white; }
        .btn-quick-success { background-color: #10b981; color: white; text-decoration: none; }
        .card-table { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.02); overflow: hidden; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="index.php?menu=dashboard">
                <i data-lucide="zap" class="me-2"></i> PORTAL ADMIN
            </a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item"><a class="nav-link active fw-bold" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-muted" href="index.php?menu=kelola_artikel">Kelola Artikel</a></li>
                    
                    <li class="nav-item d-flex align-items-center gap-3 border-start ps-3">
                        <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill">
                            <span class="small fw-bold text-dark"><?php echo $_SESSION['username']; ?></span>
                            <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=4f46e5&color=fff" class="rounded-circle" width="28">
                        </div>
                        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                            <i data-lucide="log-out" style="width: 16px;"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<?php
include"menu.php";
?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>