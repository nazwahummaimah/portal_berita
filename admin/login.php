<?php
// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .card-login { margin-top: 80px; border-radius: 15px; border: none; }
        .captcha-box { background: #eee; border-radius: 5px; padding: 5px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card card-login shadow-lg">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">ADMIN LOGIN</h3>
                        <p class="text-muted small">Silakan masuk untuk mengelola berita</p>
                    </div>

                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Keamanan (Captcha)</label>
                            <div class="d-flex align-items-center mb-2">
                                <div class="captcha-box me-2">
                                    <img src="captcha.php" alt="captcha" id="img-captcha">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('img-captcha').src='captcha.php?' + Math.random();">
                                    <i class="bi bi-arrow-clockwise">Refresh</i>
                                </button>
                            </div>
                            <input type="text" name="input_captcha" class="form-control" placeholder="Ketik kode di atas" required autocomplete="off">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Masuk Sekarang</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="text-center mt-3">
                <a href="../index.php" class="text-decoration-none text-muted small">← Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>