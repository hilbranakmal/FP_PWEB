<h1 align="center">LAPORAN PROYEK PEMROGRAMMAN WEB</h1>

<p align="center">
  <img src="https://github.com/user-attachments/assets/0d261a49-ae0b-405e-8cef-12b33a7571cb" width="500" alt="Logo ITS">
</p>


**JUDUL PROYEK:**
Rancang Bangun Aplikasi Manajemen Tugas "StudyPlanner" Berbasis Web dengan Integrasi Google Calendar dan Notifikasi Otomatis

**TIM PENGEMBANG:**<br>
**Muhammad Hilbran Akmal Abrar (5025241052)** [Frontend & UI]<br>
**Reza Afzaal Faizullah Taqy (5025241051** [Backend & Logic]

---

## BAB 1: PENDAHULUAN

### 1.1 Latar Belakang
Dalam dunia akademik dan profesional, manajemen waktu adalah kunci produktivitas. Seringkali, mahasiswa atau pekerja kesulitan melacak tenggat waktu (*deadline*) tugas, membedakan prioritas, dan mengatur tugas yang bersifat rutin. Meskipun banyak aplikasi *to-do list* tersedia, dibutuhkan solusi yang dapat terintegrasi langsung dengan kalender digital dan memberikan pengingat proaktif melalui email. Oleh karena itu, dikembangkanlah **"StudyPlanner"**, sebuah aplikasi berbasis web yang dirancang untuk mengatasi masalah manajemen tugas tersebut.

### 1.2 Rumusan Masalah
Bagaimana membangun aplikasi *To-Do List* yang tidak hanya mencatat tugas, tetapi juga memiliki fitur tugas rutin (*recurring*), dan integrasi kalender eksternal?

### 1.3 Tujuan
1. Menyediakan *platform* pencatatan tugas yang terstruktur dengan sistem prioritas.
2. Membuat logika tugas berulang (*recurring tasks*) yang otomatis diperbarui.
3. Mengintegrasikan sistem dengan Google Calendar API untuk sinkronisasi jadwal.

---

## BAB 2: ANALISIS DAN PERANCANGAN SISTEM

### 2.1 Fitur Utama (*Functional Requirements*)
Aplikasi ini memiliki fitur-fitur unggulan sebagai berikut:
* **Manajemen Pengguna (Auth):** Pendaftaran dan Login pengguna yang aman.
* **Manajemen Tugas (CRUD):** Pengguna dapat membuat, membaca, mengedit, dan menghapus tugas.
* **Sistem Prioritas:** Pengkategorian tugas berdasarkan tingkat urgensi (*Hard/High*, *Medium*, *Normal*).
* **Reminder System (H-3):** Sistem secara otomatis mengecek tanggal tugas dan mengirimkan notifikasi email jika *deadline* tersisa 3 hari.
* **Recurring Tasks (Tugas Rutinan):** Fitur khusus di mana tugas tidak hilang setelah selesai, melainkan otomatis dijadwalkan ulang untuk siklus berikutnya.
* **Integrasi Google Calendar API:** Sinkronisasi otomatis antara tugas di aplikasi dengan Google Calendar pengguna.

### 2.2 Arsitektur Sistem
Aplikasi dibangun dengan struktur yang terorganisir untuk memudahkan pemeliharaan:

<img width="293" height="652" alt="image" src="https://github.com/user-attachments/assets/30a262ab-31f1-495d-a4e3-921abd6c401e" /><br>
* **Backend:** PHP Native (Logika bisnis & API).
* **Frontend:** HTML, CSS, JavaScript.
* **Database:** MySQL.
* **Hosting:** Railway (Cloud Server).
* **Struktur Direktori:**
    * `/api`: Endpoint integrasi kalender & fungsi asinkron.
    * `/config`: Koneksi database (`db.php`).
    * `/assets`: File foto logo (`icon.png logo.png`).
    * `/includes`: Komponen UI modular (`header.php`, `footer.php`).
    * `/public`: Aset statis (CSS, JS, Images).

---

## BAB 3: IMPLEMENTASI DAN PEMBAHASAN

### 3.1 Implementasi Antarmuka (*User Interface*)
Antarmuka pengguna didesain dengan pendekatan minimalis (*clean design*).
<img width="1863" height="1014" alt="image" src="https://github.com/user-attachments/assets/d45ad9b0-efb3-48c2-b750-ee0d67339dd4" />
* **Dashboard:** Menampilkan ringkasan tugas dalam status *Ongoing* dan *Selesai*.
* **Kartu Tugas:** Tugas ditampilkan dalam bentuk kartu yang memuat Judul, Deadline, dan Label Prioritas (misal: *High Priority* berwarna merah).

### 3.2 Implementasi Fitur Notifikasi Email (H-3)
Logika *backend* dijalankan secara berkala untuk membandingkan tanggal saat ini dengan tanggal *deadline*.
> **Logika:** Jika `(Deadline - Tanggal Sekarang) == 3 hari`, sistem memicu fungsi kirim email (menggunakan PHPMailer) ke alamat email pengguna.

### 3.3 Implementasi Tugas Rutinan (*Recurring Tasks*)
Berbeda dengan tugas biasa, tugas rutin memiliki penanganan logika khusus:
1.  Saat tugas rutin ditandai selesai atau melewati batas waktu, sistem **tidak menghapusnya**.
2.  Sistem memperbarui atribut `due_date` tugas tersebut ke periode berikutnya secara otomatis.

### 3.4 Integrasi Google Calendar API
Sistem menggunakan autentikasi **OAuth 2.0**. Saat tugas baru dibuat, aplikasi mengirimkan *request* ke API Google Calendar untuk membuat *event* baru secara otomatis, memastikan pengguna tidak perlu input ganda.

---

## BAB 4: PENGUJIAN DAN DEPLOYMENT

### 4.1 Lingkungan Hosting
Aplikasi telah berhasil di-*deploy* dan dapat diakses secara *online*.
* **Platform:** Railway.app
* **Status Database:** Terkoneksi dan aktif.

### 4.2 Hasil Pengujian
| Fitur | Hasil Tes | Status |
| :--- | :--- | :---: |
| **Pendaftaran Akun** | Data user tersimpan di DB | ✅ Berhasil |
| **Login Akun** | User bisa login menggunakan email yang digunakan saat pendaftaran | ✅ Berhasil |
| **Menambahkan Tugas** | Tugas muncul di dashboard dengan desain kartu | ✅ Berhasil |
| **Prioritas Tugas** | Label muncul sesuai input (Hard/Medium/Low) | ✅ Berhasil |
| **Deadline Tugas** | Deadline muncul sesuai input | ✅ Berhasil |
| **Update Tugas** | Tugas bisa diubah setelah ditambahkan | ✅ Berhasil |
| **Menghapus Tugas** | Tugas yang dirasa tidak perlu bisa dihapus oleh user | ✅ Berhasil |

---

## BAB 5: PENUTUP

### 5.1 Kesimpulan
Proyek **"StudyPlanner"** berhasil dikembangkan sesuai spesifikasi. Aplikasi ini berfungsi sebagai asisten pribadi digital yang tidak hanya mencatat, tetapi juga aktif mengingatkan dan membantu sinkronisasi jadwal. Penggunaan arsitektur kode yang terstruktur memudahkan pengembangan lebih lanjut di masa depan.

---
<center>
Copyright © 2025 StudyPlanner. Dibuat oleh Akmal & Reza.
</center>
