<?php
// api/cron_email.php

// 1. Load Database & Library
// Pastikan path ini benar. Jika file ini ada di folder 'api', maka naik satu level (..) untuk ke root.
require_once '../config/db.php'; 
require '../vendor/autoload.php'; // Ini otomatis ada setelah Railway membaca composer.json

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Setup Tanggal
$today = date('Y-m-d');
$h_min_3 = date('Y-m-d', strtotime($today . ' + 3 days'));

echo "Memeriksa tugas untuk deadline: $h_min_3 <br><br>";

// 3. Ambil Data Tugas H-3
$sql = "SELECT t.*, u.email, u.name 
        FROM tasks t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.deadline = ? AND t.status = 'ongoing'";

$stmt = $pdo->prepare($sql);
$stmt->execute([$h_min_3]);
$tasks = $stmt->fetchAll();

if (count($tasks) > 0) {
    // Siapkan PHPMailer
    $mail = new PHPMailer(true);

    try {
        // --- KONFIGURASI SERVER SMTP (GMAIL) ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        // Ambil credential dari Environment Variable Railway (Aman)
        $mail->Username   = getenv('SMTP_EMAIL'); 
        $mail->Password   = getenv('SMTP_PASSWORD'); 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Identitas Pengirim
        $mail->setFrom(getenv('SMTP_EMAIL'), 'StudyPlanner Bot');

        foreach ($tasks as $task) {
            // Reset penerima untuk iterasi berikutnya
            $mail->clearAddresses();
            
            // Tambah Penerima
            $mail->addAddress($task['email'], $task['name']);

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = "Reminder: Tugas H-3 Deadline!";
            
            // Body Email (HTML)
            $body  = "<h3>Halo " . htmlspecialchars($task['name']) . "!</h3>";
            $body .= "<p>Jangan lupa, tugas kamu <b>'" . htmlspecialchars($task['task_name']) . "'</b> akan tenggat waktu pada tanggal <b>" . $task['deadline'] . "</b>.</p>";
            $body .= "<p>Semangat mengerjakannya!</p>";
            $body .= "<br><small>Dikirim otomatis oleh StudyPlanner.</small>";
            
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body); // Versi teks biasa jika HTML tidak didukung

            // Kirim!
            $mail->send();
            echo "✅ Sukses mengirim email ke: " . $task['email'] . "<br>";
        }
    } catch (Exception $e) {
        echo "❌ Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Tidak ada tugas H-3 hari ini.";
}
?>