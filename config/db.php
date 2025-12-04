<?php
// Gunakan getenv() untuk mengambil data dari Railway
// Jika di localhost (tidak ada env), gunakan fallback ke default XAMPP
$host = getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost';
$user = getenv('DB_USER') ? getenv('DB_USER') : 'root';
$pass = getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : ''; // password kosong di xampp
$db   = getenv('DB_NAME') ? getenv('DB_NAME') : 'todolist_db';
$port = getenv('DB_PORT') ? getenv('DB_PORT') : 3307;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>