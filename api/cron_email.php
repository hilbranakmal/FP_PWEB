<?php
// api/cron_email.php

// 1. Load Database & Library
require_once '../config/db.php'; 
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- PENTING: SET TIMEZONE KE INDONESIA ---
// Agar "Hari Ini" menurut server sama dengan "Hari Ini" di jam tanganmu.
date_default_timezone_set('Asia/Jakarta'); 

// 2. Setup Tanggal
$today = date('Y-m-d');
$h_min_3 = date('Y-m-d', strtotime($today . ' + 3 days'));

echo "Tanggal Server Hari Ini: <b>$today</b><br>";
echo "Mencari tugas dengan deadline: <b>$h_min_3</b> <br><hr>";

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
        $mail->Username   = getenv('SMTP_EMAIL'); 
        $mail->Password   = getenv('SMTP_PASSWORD'); 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(getenv('SMTP_EMAIL'), 'StudyPlanner Bot');

        foreach ($tasks as $task) {
            $mail->clearAddresses();
            $mail->addAddress($task['email'], $task['name']);

            $mail->isHTML(true);
            $mail->Subject = "Reminder: Tugas H-3 Deadline!";
            
            $body  = "<h3>Halo " . htmlspecialchars($task['name']) . "!</h3>";
            $body .= "<p>Jangan lupa, tugas kamu <b>'" . htmlspecialchars($task['task_name']) . "'</b> akan tenggat waktu pada tanggal <b>" . $task['deadline'] . "</b>.</p>";
            $body .= "<p>Semangat mengerjakannya!</p>";
            $body .= "<br><small>Dikirim otomatis oleh StudyPlanner.</small>";
            
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            echo "✅ Sukses mengirim email ke: " . $task['email'] . " (Task: " . $task['task_name'] . ")<br>";
        }
    } catch (Exception $e) {
        echo "❌ Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Tidak ada tugas dengan deadline tanggal $h_min_3.";
}
?>