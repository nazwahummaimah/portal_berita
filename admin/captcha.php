<?php
session_start();

// 1. Generate kode acak
$kode_acak = substr(str_shuffle("0123456789abcdefghijkmnopqrstuvwxyz"), 0, 4);

// 2. Simpan kode ke dalam SESSION agar bisa dicek saat login nanti
$_SESSION['captcha_code'] = $kode_acak;

// 3. Membuat gambar dengan PHP GD Library
$lebar = 100;
$tinggi = 40;
$gambar = imagecreate($lebar, $tinggi);

// 4. Menentukan warna (RGB)
$warna_bg    = imagecolorallocate($gambar, 235, 235, 235); // Abu-abu terang
$warna_teks  = imagecolorallocate($gambar, 0, 0, 0);       // Hitam
$warna_garis = imagecolorallocate($gambar, 200, 200, 200); // Garis samaran

// 5. Tambahkan garis-garis pengganggu agar bot susah baca
for($i=0; $i<5; $i++) {
    imageline($gambar, 0, rand(0,$tinggi), $lebar, rand(0,$tinggi), $warna_garis);
}

// 6. Tulis kode acak ke gambar
// Parameter: gambar, ukuran font (1-5), x, y, teks, warna
imagestring($gambar, 5, 20, 12, $kode_acak, $warna_teks);

// 7. Beritahu browser bahwa ini adalah gambar, bukan teks HTML
header("Content-type: image/png");
imagepng($gambar);
imagedestroy($gambar);
?>