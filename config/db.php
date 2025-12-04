<?php
// db.php
// Koneksi Khusus PostgreSQL untuk Railway

// Ambil variabel environment dari Railway (atau default ke localhost jika testing lokal)
// Railway menyediakan variabel khusus untuk Postgres: PGHOST, PGUSER, PGDATABASE, PGPASSWORD, PGPORT
$host = getenv('PGHOST') ? getenv('PGHOST') : 'localhost';
$port = getenv('PGPORT') ? getenv('PGPORT') : '5432';
$db   = getenv('PGDATABASE') ? getenv('PGDATABASE') : 'nama_db_lokal';
$user = getenv('PGUSER') ? getenv('PGUSER') : 'postgres';
$pass = getenv('PGPASSWORD') ? getenv('PGPASSWORD') : 'password_lokal';

try {
    // String koneksi (DSN) untuk PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    
    // Membuat koneksi PDO
    $conn = new PDO($dsn, $user, $pass);
    
    // Set mode error agar jika ada masalah, PHP akan memberitahu
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // (Opsional) Set fetch mode default ke Associative Array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>