<?php
// api/cron_email.php

// 1. Load Database & Library
require_once '../config/db.php'; 
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Tambah class SMTP buat debug

// Perpanjang durasi eksekusi jadi 60 detik (default 30s sering timeout)
set_time_limit(60);

// --- SET TIMEZONE ---
date_default_timezone_set('Asia/Jakarta'); 

$today = date('Y-m-d');
$h_min_3 = date('Y-m-d', strtotime($today . ' + 3 days'));

echo "<h2>Mode Debugging Email</h2>";
echo "Tanggal Server Hari Ini: <b>$today</b><br>";
echo "Mencari tugas deadline: <b>$h_min_3</b> <br><hr>";

$sql = "SELECT t.*, u.email, u.name 
        FROM tasks t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.deadline = ? AND t.status = 'ongoing'";

$stmt = $pdo->prepare($sql);
$stmt->execute([$h_min_3]);
$tasks = $stmt->fetchAll();

if (count($tasks) > 0) {
    $mail = new PHPMailer(true);

    try {
        // --- KONFIGURASI SMTP GMAIL ---
        // Aktifkan Debugging agar muncul text detail di browser
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_EMAIL'); 
        $mail->Password   = getenv('SMTP_PASSWORD'); 
        
        // KITA COBA GANTI KE PORT 465 (SMTPS) BIAR LEBIH STABIL
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        $mail->setFrom(getenv('SMTP_EMAIL'), 'StudyPlanner Bot');

        foreach ($tasks as $task) {
            $mail->clearAddresses();
            $mail->addAddress($task['email'], $task['name']);

            $mail->isHTML(true);
            $mail->Subject = "Reminder: Tugas H-3 Deadline!";
            
            $body  = "<h3>Halo " . htmlspecialchars($task['name']) . "!</h3>";
            $body .= "<p>Tugas <b>'" . htmlspecialchars($task['task_name']) . "'</b> deadline pada <b>" . $task['deadline'] . "</b>.</p>";
            
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            echo "Mencoba mengirim ke: " . $task['email'] . "...<br>";
            $mail->send();
            echo "✅ <b>BERHASIL TERKIRIM!</b><br><br>";
        }
    } catch (Exception $e) {
        echo "<br>❌ <b>GAGAL KIRIM EMAIL</b><br>";
        echo "Pesan Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Tidak ada tugas dengan deadline tanggal $h_min_3.";
}
?>