<div class="bg-white p-4 shadow-sm rounded-1 mb-4">
    <h5 class="section-title mb-4">Jelajahi Kategori</h5>
    <div class="d-flex flex-wrap gap-2">
        <?php 
        $kategori_side = mysqli_query($conn, "SELECT * FROM kategori");
        while($c = mysqli_fetch_assoc($kategori_side)): 
        ?>
            <a href="index.php?menu=home&kategori=<?= $c['id_kategori'] ?>" 
               class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 fw-bold" 
               style="font-size: 0.75rem; color: #555; border-color: #eee; transition: 0.3s;">
                <?= $c['nama_kategori'] ?>
            </a>
        <?php endwhile; ?>
    </div>
</div>