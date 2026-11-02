# 🚀 Panduan Lengkap Deploy LEMAS ke Server VPS Linux (Ubuntu 24.04 LTS)

Panduan ini disusun khusus untuk guru dan pengelola sekolah yang baru pertama kali mengelola server VPS Linux. Setiap langkah dijelaskan secara bertahap, mudah dipahami, dan dapat langsung disalin-tempel (*copy-paste*) ke terminal server VPS Anda.

---

## 📌 Ringkasan Spesifikasi Server yang Disarankan
- **Sistem Operasi**: Ubuntu 24.04 LTS (atau 22.04 LTS) 64-bit
- **Spesifikasi Minimal VPS**:
  - RAM: 1 GB (Disarankan 2 GB agar leluasa)
  - CPU: 1 vCPU / Core
  - Penyimpanan: 20 GB SSD
- **Nama Domain / Subdomain**: Contoh: `lms.sekolah.sch.id` (arahkan A Record domain ke IP Public VPS Anda).

---

## 🧭 Langkah 1: Akses Server & Pembaruan Sistem

1. Buka aplikasi terminal di laptop Anda (PowerShell, Command Prompt, atau Terminal di Mac/Linux).
2. Hubungkan ke VPS menggunakan SSH:
   ```bash
   ssh root@IP_ADDRESS_VPS_ANDA
   ```
   *(Ganti `IP_ADDRESS_VPS_ANDA` dengan alamat IP server Anda, lalu masukkan kata sandi VPS).*

3. Perbarui seluruh paket sistem ke versi paling baru:
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

4. Pasang beberapa utilitas pendukung:
   ```bash
   sudo apt install -y curl git unzip zip software-properties-common ufw
   ```

5. Aktifkan firewall agar server aman (buka port SSH, HTTP, dan HTTPS):
   ```bash
   sudo ufw allow OpenSSH
   sudo ufw allow 'Nginx Full'
   sudo ufw --force enable
   ```

---

## 🛠️ Langkah 2: Instalasi Komponen Web Server (Nginx, PHP 8.4, & MySQL)

### 1. Pasang Web Server Nginx
```bash
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

### 2. Pasang PHP 8.4 dan Ekstensi yang Dibutuhkan
Tambahkan repositori resmi PHP Ondrej Sury untuk mendapatkan PHP 8.4 terbaru:
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4-fpm php8.4-cli php8.4-common php8.4-mysql php8.4-sqlite3 \
    php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip php8.4-gd php8.4-intl php8.4-bcmath
```

Pastikan PHP-FPM aktif:
```bash
sudo systemctl enable php8.4-fpm
sudo systemctl start php8.4-fpm
```

### 3. Pasang Composer (Manajer Paket PHP)
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

### 4. Pasang Database Server MySQL
```bash
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql
```

---

## 🗄️ Langkah 3: Pembuatan Database MySQL untuk LEMAS

1. Masuk ke baris perintah MySQL:
   ```bash
   sudo mysql
   ```

2. Jalankan perintah SQL berikut untuk membuat database dan pengguna khusus aplikasi LEMAS:
   *(Ganti `GantiDenganSandiKuat123!` dengan kata sandi rahasia pilihan Anda)*:
   ```sql
   CREATE DATABASE lemas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'lemas_user'@'localhost' IDENTIFIED BY 'GantiDenganSandiKuat123!';
   GRANT ALL PRIVILEGES ON lemas_db.* TO 'lemas_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

---

## 📥 Langkah 4: Mengunduh / Clone Proyek dari GitHub

1. Pindah ke direktori web server Linux:
   ```bash
   cd /var/www
   ```

2. Unduh (*clone*) kode repositori GitHub Anda:
   ```bash
   sudo git clone https://github.com/USERNAME_ANDA/REPOSITORI_ANDA.git lemas
   ```
   *(Ganti tautan di atas dengan alamat repositori GitHub proyek LEMAS Anda)*.

3. Masuk ke dalam folder aplikasi:
   ```bash
   cd /var/www/lemas
   ```

---

## 📦 Langkah 5: Pemasangan Dependensi PHP

Jalankan perintah Composer untuk mengunduh semua library pendukung dengan mode produksi (ringan & cepat):
```bash
composer install --no-dev --optimize-autoloader
```

---

## ⚙️ Langkah 6: Konfigurasi File Lingkungan Produksi (`.env`)

1. Salin file contoh konfigurasi ke file `.env` aktif:
   ```bash
   cp .env.example .env
   ```

2. Buka dan edit file `.env` menggunakan teks editor terminal `nano`:
   ```bash
   nano .env
   ```

3. Sesuaikan baris-baris berikut:
   ```ini
   APP_NAME="LEMAS - LMS Sekolah"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://lms.sekolah.sch.id

   APP_TIMEZONE=Asia/Jakarta
   APP_LOCALE=id
   APP_FALLBACK_LOCALE=id

   # Konfigurasi Database MySQL
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lemas_db
   DB_USERNAME=lemas_user
   DB_PASSWORD=GantiDenganSandiKuat123!
   ```
   *Tekan `Ctrl + O` lalu `Enter` untuk menyimpan, lalu `Ctrl + X` untuk keluar dari editor nano.*

4. Buat kunci keamanan aplikasi (*Application Key*):
   ```bash
   php artisan key:generate --force
   ```

5. Hubungkan folder penyimpanan unggahan file materi dan tugas ke folder publik:
   ```bash
   php artisan storage:link
   ```

6. Jalankan migrasi tabel database:
   ```bash
   php artisan migrate --force
   ```

7. *(Opsional)* Jika Anda ingin langsung memasukkan akun guru dan murid contoh untuk pengujian awal:
   ```bash
   php artisan db:seed --force
   ```

---

## 🌐 Langkah 7: Konfigurasi Web Server Nginx

1. Buat file konfigurasi virtual host baru untuk LEMAS:
   ```bash
   sudo nano /etc/nginx/sites-available/lemas
   ```

2. Salin dan tempel konfigurasi berikut:
   *(Ganti `lms.sekolah.sch.id` dengan nama domain atau IP server Anda)*:
   ```nginx
   server {
       listen 80;
       listen [::]:80;
       server_name lms.sekolah.sch.id;
       root /var/www/lemas/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php index.html;

       charset utf-8;

       # Batas maksimal ukuran unggah berkas tugas/materi (misal 20 MB)
       client_max_body_size 20M;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/run/php/php8.4-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
           fastcgi_hide_header X-Powered-By;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
   *Tekan `Ctrl + O` lalu `Enter` untuk menyimpan, lalu `Ctrl + X` untuk keluar.*

3. Aktifkan konfigurasi dengan membuat tautan pintas (*symlink*):
   ```bash
   sudo ln -s /etc/nginx/sites-available/lemas /etc/nginx/sites-enabled/
   ```

4. Nonaktifkan situs default Nginx bawaan agar tidak bentrok:
   ```bash
   sudo rm -f /etc/nginx/sites-enabled/default
   ```

5. Uji konfigurasi Nginx:
   ```bash
   sudo nginx -t
   ```
   *(Harus muncul pesan: `syntax is ok` dan `test is successful`).*

6. Muat ulang Nginx:
   ```bash
   sudo systemctl reload nginx
   ```

---

## 🔒 Langkah 8: Pengaturan Hak Akses Folder & Berkas (Permissions)

Web server Linux berjalan di bawah pengguna `www-data`. Berikan hak akses kepemilikan agar Nginx dan PHP dapat menulis file log, cache, serta file unggahan materi dan tugas:

```bash
cd /var/www/lemas
sudo chown -R www-data:www-data /var/www/lemas
sudo find /var/www/lemas -type f -exec chmod 644 {} \;
sudo find /var/www/lemas -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/lemas/storage /var/www/lemas/bootstrap/cache
```

---

## 🔐 Langkah 9: Pasang Sertifikat SSL Gratis (HTTPS)

Gunakan **Certbot** dari Let's Encrypt untuk mengamankan situs sekolah Anda dengan gembok hijau HTTPS secara gratis:

1. Pasang Certbot:
   ```bash
   sudo apt install -y certbot python3-certbot-nginx
   ```

2. Jalankan perintah otomatis untuk membuat dan memasang sertifikat SSL:
   ```bash
   sudo certbot --nginx -d lms.sekolah.sch.id
   ```
   *(Ganti `lms.sekolah.sch.id` dengan domain Anda. Ikuti petunjuk di layar: masukkan email Anda dan setujui syarat layanan).*

3. Certbot akan otomatis memperbarui sertifikat SSL sebelum kedaluwarsa secara mandiri.

---

## ⚡ Langkah 10: Optimasi Performa Produksi Laravel & Filament

Agar aplikasi terbuka sangat cepat dan hemat konsumsi memori server, aktifkan sistem *caching*:

```bash
cd /var/www/lemas
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
```

> **Catatan**: Jika Anda mengubah isi file `.env` di kemudian hari, jalankan `php artisan config:clear` terlebih dahulu sebelum mengaktifkannya kembali dengan `php artisan config:cache`.

---

## 💾 Langkah 11: Otomatisasi Backup Database (Pencadangan Data)

Sangat penting bagi sekolah untuk memiliki cadangan data otomatis setiap hari.

1. Buat folder penyimpanan backup:
   ```bash
   sudo mkdir -p /var/backups/lemas
   sudo chmod 700 /var/backups/lemas
   ```

2. Buat skrip pencadangan otomatis:
   ```bash
   sudo nano /usr/local/bin/backup-lemas.sh
   ```

3. Tempel isi skrip berikut:
   ```bash
   #!/bin/bash
   BACKUP_DIR="/var/backups/lemas"
   DATE=$(date +"%Y-%m-%d_%H%M%S")
   DB_USER="lemas_user"
   DB_PASS="GantiDenganSandiKuat123!"
   DB_NAME="lemas_db"

   # Buat file backup database terkompresi .sql.gz
   mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > "$BACKUP_DIR/lemas_db_$DATE.sql.gz"

   # Hapus backup yang lebih lama dari 14 hari agar harddisk server tidak penuh
   find $BACKUP_DIR -type f -name "lemas_db_*.sql.gz" -mtime +14 -delete
   ```
   *Simpan dengan `Ctrl + O`, lalu `Ctrl + X`.*

4. Beri izin eksekusi pada skrip backup:
   ```bash
   sudo chmod +x /usr/local/bin/backup-lemas.sh
   ```

5. Daftarkan tugas otomatis (*Cron Job*) agar berjalan setiap malam pukul 02:00 WIB:
   ```bash
   sudo crontab -e
   ```
   Tambahkan baris berikut di paling bawah:
   ```cron
   0 2 * * * /usr/local/bin/backup-lemas.sh > /dev/null 2>&1
   ```

---

## 🔄 Langkah 12: Panduan Memperbarui Aplikasi di Kemudian Hari

Jika ada perubahan kode atau fitur baru yang sudah Anda *push* ke GitHub:

1. Masuk ke folder aplikasi di VPS:
   ```bash
   cd /var/www/lemas
   ```
2. Tarik pembaruan dari GitHub:
   ```bash
   git pull origin main
   ```
3. Pasang library baru (jika ada):
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
4. Jalankan pembaruan tabel database:
   ```bash
   php artisan migrate --force
   ```
5. Perbarui aset antarmuka Filament:
   ```bash
   php artisan filament:upgrade
   ```
6. Muat ulang cache performa:
   ```bash
   php artisan optimize
   ```

---

## 🩺 Pusat Bantuan & Troubleshooting Masalah Umum

### 1. Muncul Tulisan "500 Server Error"
- Periksa log error Laravel:
  ```bash
  tail -n 50 /var/www/lemas/storage/logs/laravel.log
  ```
- Pastikan hak akses folder storage sudah tepat:
  ```bash
  sudo chown -R www-data:www-data /var/www/lemas/storage /var/www/lemas/bootstrap/cache
  sudo chmod -R 775 /var/www/lemas/storage /var/www/lemas/bootstrap/cache
  ```

### 2. Gagal Mengunggah Berkas Ukuran Besar (> 2 MB)
Buka file konfigurasi PHP:
```bash
sudo nano /etc/php/8.4/fpm/php.ini
```
Cari dan ubah nilai berikut:
```ini
upload_max_filesize = 20M
post_max_size = 25M
```
Lalu muat ulang PHP-FPM:
```bash
sudo systemctl restart php8.4-fpm
```

### 3. Tampilan Halaman Rusak / Tidak Ada Gaya CSS
Jalankan perintah publish aset Filament:
```bash
cd /var/www/lemas
php artisan filament:assets
php artisan storage:link
```

---

**Selamat! LMS LEMAS Anda kini telah aktif dan siap digunakan secara aman di server VPS sekolah Anda.** 🎓
