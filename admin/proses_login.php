<?php
session_start();
include 'koneksi.php';

$username       = mysqli_real_escape_string($conn, $_POST['username']);
$password       = $_POST['password'];
$input_captcha  = $_POST['input_captcha'];
$sistem_captcha = $_SESSION['captcha_code'];

// ✅ CEK CAPTCHA
if ($input_captcha !== $sistem_captcha) {
    echo "<script>alert('KODE CAPTCHA SALAH!'); window.location='login.php';</script>";
    exit();
}

// ✅ CEK DATABASE
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($query);

if ($data) {

    $_SESSION['id_user']  = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['password']  = $data['password'];
    $_SESSION['role']     = $data['role'];

    // ✅ SESUAIKAN PATH
    header("location: index.php"); 
    exit();

} else {
    echo "<script>alert('Username atau Password Salah!'); window.location='login.php';</script>";
}
?>