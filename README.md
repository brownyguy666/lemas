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

---

## 👥 Akun Contoh untuk Pengujian (Seeder)
Semua akun contoh di bawah ini menggunakan kata sandi seragam: **`password`**

| Peran | Nama | Email | Panel Tujuan |
|---|---|---|---|
| **Admin** | Administrator LEMAS | `admin@lemas.sch.id` | `/admin` |
| **Guru 1** | Budi Santoso, S.Pd. | `budi.guru@lemas.sch.id` | `/guru` |
| **Guru 2** | Siti Rahmawati, M.Pd. | `siti.guru@lemas.sch.id` | `/guru` |
| **Murid** | Ahmad Dahlan | `murid01@lemas.sch.id` | `/murid` |
| **Murid** | Annisa Tri Utami | `murid02@lemas.sch.id` | `/murid` |
| *(Murid 3–10)* | *(Bagas s/d Indah)* | `murid03@lemas.sch.id` s/d `murid10@lemas.sch.id` | `/murid` |

> **Catatan Keamanan**: Setiap peran terkunci di panelnya masing-masing. Jika murid mencoba membuka `/admin` atau `/guru`, sistem akan langsung menolak dengan kode keamanan 403 (Dilarang Masuk).

---

## 🏫 Ruang Kelas Contoh (Tersedia untuk Dicoba)

| Nama Kelas | Guru Pengampu | Kode Gabung | Murid Terdaftar |
|---|---|---|---|
| **Matematika Kelas 7A** | Budi Santoso, S.Pd. | `MTK7A1` | Murid 01 s/d 05 |
| **IPA Terpadu Kelas 7A** | Siti Rahmawati, M.Pd. | `IPA7A1` | Murid 01 s/d 05 |
| **Matematika Kelas 7B** | Budi Santoso, S.Pd. | `MTK7B1` | Murid 06 s/d 10 |

> **Uji Coba Gabung Kelas**:
> - Login sebagai `murid01@lemas.sch.id`. Anda otomatis melihat *Matematika 7A* dan *IPA Terpadu 7A*.
> - Klik tombol **"Gabung Kelas dengan Kode"**, lalu masukkan kode `MTK7B1`.
> - Anda akan langsung terdaftar ke kelas *Matematika 7B*!

---

## 📚 Contoh Materi Pelajaran (Siap Dicoba)

| Judul Materi | Tipe Materi | Kelas | Tindakan Murid |
|---|---|---|---|
| **Pengenalan Aljabar dan Variabel** | Teks / Bacaan | Matematika Kelas 7A | Klik **Baca Materi** (muncul jendela pop-up materi yang nyaman dibaca) |
| **Video: Trik Cepat Bilangan Bulat** | Video YouTube | Matematika Kelas 7A | Klik **Tonton Video** (langsung membuka video di tab baru) |
| **Modul Belajar: Ekosistem** | Berkas Dokumen (PDF) | IPA Terpadu Kelas 7A | Klik **Unduh Berkas** (mengunduh berkas secara aman) |

> **Keamanan Berkas**: Seluruh berkas materi memiliki jalur unduhan aman dengan verifikasi hak akses. Murid di luar kelas tidak dapat mengunduh berkas kelas lain.

---

## 📝 Contoh Tugas & Pengumpulan (Siap Dicoba)

Aplikasi memiliki fitur penugasan dan penilaian lengkap dengan deteksi keterlambatan otomatis dan privasi nilai:

| Judul Tugas | Kelas | Tenggat Waktu | Nilai Maksimal | Status Contoh di Database |
|---|---|---|---|---|
| **Tugas 1: Menyelesaikan Persamaan Aljabar** | Matematika Kelas 7A (Pak Budi) | Besok 23:59 WIB | 100 | Ahmad Dahlan: Nilai 95/100 (Sudah Dinilai). Annisa: Sudah Mengumpulkan (Menunggu Nilai). |
| **Laporan Pengamatan Rantai Makanan** | IPA Terpadu Kelas 7A (Bu Siti) | Kemarin 23:59 WIB | 100 | Ahmad Dahlan: Terlambat mengumpulkan (Badge merah "Terlambat"). |

### 🔍 Cara Menguji Alur Tugas & Penilaian:
1. **Sebagai Guru (Pak Budi / Bu Siti)**:
   - Masuk ke [http://localhost:8000/guru](http://localhost:8000/guru) menggunakan akun `budi.guru@lemas.sch.id` (password: `password`).
   - Buka menu **Tugas**. Klik **Ubah / Lihat** pada "Tugas 1: Menyelesaikan Persamaan Aljabar".
   - Di bagian bawah, terdapat tabel **"Daftar Pengumpulan & Penilaian Murid"**.
   - Klik aksi **"Nilai Murid"** untuk memberi nilai (0–100) dan umpan balik catatan guru.
   - Klik **"Detail Jawaban"** untuk membaca jawaban teks atau mengunduh berkas tugas murid.
2. **Sebagai Murid (Ahmad Dahlan / Annisa)**:
   - Masuk ke [http://localhost:8000/murid](http://localhost:8000/murid) menggunakan akun `murid01@lemas.sch.id` atau `murid02@lemas.sch.id`.
   - Buka menu **Tugas**.
   - Klik **"Petunjuk Soal"** untuk membaca instruksi lengkap tugas dari guru.
   - Klik **"Kumpulkan Tugas"** untuk mengirim jawaban teks atau mengunggah berkas pengerjaan (PDF, Word, Excel, Gambar hingga 10MB).
   - Klik **"Lihat Nilai"** untuk melihat nilai angka dan catatan umpan balik yang diberikan guru secara privat. Murid lain tidak dapat melihat nilai ini!

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

## 🌐 Panduan Deploy ke Server VPS (Produksi)

Aplikasi LEMAS sudah 100% siap untuk dipasang di server VPS Linux (Ubuntu 24.04 / 22.04 LTS) dengan basis data MySQL:
- **Panduan Lengkap Bertahap**: Silakan baca dokumen panduan produksi di [DEPLOYMENT.md](DEPLOYMENT.md).
- Dokumen tersebut mencakup:
  1. Instalasi paket Nginx, PHP 8.4-FPM, dan MySQL Server.
  2. Pembuatan database MySQL dan hak akses pengguna.
  3. Konfigurasi virtual host Nginx untuk Laravel & Filament.
  4. Pengaturan hak akses folder `storage` & `bootstrap/cache`.
  5. Pemasangan sertifikat SSL gratis (HTTPS) dengan Certbot.
  6. Skrip pencadangan otomatis (Backup Cron Job) setiap hari.
  7. Panduan troubleshooting masalah umum pada VPS.
