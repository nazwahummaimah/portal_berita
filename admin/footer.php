<div class="py-5"></div>

    <footer class="bg-white border-top py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                            <i data-lucide="layout-dashboard" class="text-primary" size="24"></i>
                        </div>
                        <h5 class="fw-bold m-0">Portal Admin</h5>
                    </div>
                    <p class="text-muted small lh-lg">
                        Sistem manajemen konten berita yang dirancang untuk efisiensi dan kemudahan pengelolaan artikel secara dinamis.
                    </p>
                </div>

                <div class="col-6 col-lg-2 offset-lg-2">
                    <h6 class="fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        <li><a href="index.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li><a href="index.php?menu=kelola_artikel" class="text-decoration-none text-muted">Kelola Artikel</a></li>
                        <li><a href="index.php?menu=tambah_berita" class="text-decoration-none text-muted">Tulis Berita</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-4 text-md-end">
                    <h6 class="fw-bold mb-3">Status Sistem</h6>
                    <div class="d-flex flex-column align-items-md-end gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">
                            <i data-lucide="check-circle" size="14" class="me-1"></i> System Online
                        </span>
                        <p class="text-muted small mt-2 mb-0">Server Time: <span class="text-dark"><?= date('H:i') ?> WIB</span></p>
                        <p class="text-muted small">Date: <span class="text-dark"><?= date('d M Y') ?></span></p>
                    </div>
                </div>
            </div>

            <hr class="my-4 text-muted opacity-25">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    &copy; <?= date('Y'); ?> <span class="fw-bold text-dark">Nazwa Hummaimah S.</span> All rights reserved.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">v1.0.5</span>
                    <div class="vr opacity-25" style="height: 15px;"></div>
                    <span class="text-muted small">Informatics Project</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Penting agar ikon Lucide di seluruh halaman admin ter-render
        lucide.createIcons();
    </script>

</body>
</html>