# LEMAS (Learning Management System Sekolah)

Aplikasi Learning Management System (LMS) sederhana, ringan, dan modular berbasis **Laravel 13** dan **Filament**. Dirancang khusus untuk mempermudah guru membagikan materi dan tugas, murid mengumpulkan tugas, dan guru memberikan penilaian.

---

## 📌 Ringkasan Panel Antarmuka
Aplikasi ini memiliki 3 panel ruang kerja terpisah:
- **Panel Admin** (`/admin`): Pengelolaan seluruh pengguna dan seluruh kelas.
- **Panel Guru** (`/guru`): Ruang kerja guru mengelola materi, tugas, dan nilai kelasnya.
- **Panel Murid** (`/murid`): Ruang belajar murid melihat materi, mengumpulkan tugas, dan melihat nilai diri sendiri.

---

## 🛠️ Prasyarat Sistem
Sistem di komputer/laptop Anda telah terpasang:
- **PHP**: Versi 8.4+ (dengan ekstensi `pdo_sqlite`, `pdo_mysql`, `curl`, `fileinfo`, `intl`, `mbstring`, `zip`)
- **Composer**: Manajer paket PHP
- **Node.js**: Versi 24+
- **Git**: Pengontrol versi kode

---

## 🚀 Cara Menjalankan Aplikasi di Komputer Lokal

Buka terminal (PowerShell atau Command Prompt) di folder proyek ini (`d:\learning-management-system(LEMAS)`), lalu ikuti langkah berikut:

### 1. Jalankan Server Lokal
Ketik perintah berikut:
```bash
php artisan serve
```
Server akan menyala dan menampilkan alamat: `http://127.0.0.1:8000`

### 2. Buka di Browser
Buka peramban (Google Chrome, Edge, dsb.) dan akses:
- **Halaman Utama / Login**: [http://localhost:8000/login](http://localhost:8000/login)
- **Panel Admin**: [http://localhost:8000/admin](http://localhost:8000/admin)
- **Panel Guru**: [http://localhost:8000/guru](http://localhost:8000/guru)
- **Panel Murid**: [http://localhost:8000/murid](http://localhost:8000/murid)

*(Catatan: Pada Tahap 1, panel sudah terpasang dan siap. Di Tahap 2, akun contoh untuk Admin, Guru, dan Murid akan dibuat beserta penguncian hak aksesnya).*

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Tests)
Untuk memastikan seluruh fungsi dan rute aplikasi berjalan normal tanpa error:
```bash
php artisan test
```

---

## 📤 Panduan Mengunggah (Push) ke GitHub

Jika Anda ingin menyimpan kode proyek ini ke repositori GitHub Anda:

1. Buka [GitHub](https://github.com) dan buat repositori baru (misalnya dengan nama `lms-sekolah` atau `lemas`).
2. Jangan centang opsi "Add a README file" saat membuat di GitHub (karena file README sudah ada di sini).
3. Salin URL repositori Anda (contoh: `https://github.com/username-anda/lemas.git`).
4. Buka terminal di folder proyek ini, lalu jalankan perintah berikut secara berurutan:
```bash
git remote add origin https://github.com/username-anda/lemas.git
git branch -M main
git push -u origin main
```

---

## 🌐 Persiapan Deploy ke Server VPS (Produksi)
Aplikasi ini sudah dipersiapkan agar mudah dipasang di server VPS Linux (Ubuntu/Debian):
- File konfigurasi `.env.example` sudah disesuaikan dengan zona waktu Indonesia (`Asia/Jakarta`) dan bahasa Indonesia (`id`).
- Konfigurasi database diatur agar secara otomatis menggunakan SQLite di laptop lokal dan siap beralih ke MySQL di server VPS.
- Panduan lengkap instalasi ke VPS akan disediakan secara mendalam pada **Tahap 6**.
