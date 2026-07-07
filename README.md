# Sistem Data Statistik Disdukcapil Tegal

Sistem Data Statistik Layanan Dokumen Kependudukan dan Pencatatan Sipil (Disdukcapil) Kabupaten Tegal. Aplikasi berbasis web ini dibangun menggunakan Laravel, Tailwind CSS, Alpine.js, dan Chart.js untuk memvisualisasikan data pelayanan secara interaktif dan *real-time*.

## Fitur Utama

- **Dashboard Publik**: Menampilkan visualisasi data pelayanan berupa statistik, grafik (*donut chart*), tabel rekapitulasi, rincian per titik layanan, dan peringkat (Kecamatan, MPP, Dinas).
- **Admin Panel**: Halaman khusus admin untuk menambah, mengedit, dan menghapus data lokasi maupun jenis layanan secara dinamis.
- **Real-time Updates**: Pengubahan data di panel Admin menggunakan Alpine.js untuk menghitung dan memperbarui total secara langsung (real-time) sebelum disimpan ke *database*.
- **Desain Responsif**: Antarmuka modern dan responsif yang teroptimasi untuk berbagai ukuran layar.

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL / MariaDB

## Instalasi

1. Klon repositori ini:
   ```bash
   git clone https://github.com/Charboros/SistemDataStatistikDisdukcapilTegal.git
   cd SistemDataStatistikDisdukcapilTegal
   ```

2. Instal dependensi PHP menggunakan Composer:
   ```bash
   composer install
   ```

3. Instal dependensi JavaScript & CSS:
   ```bash
   npm install
   ```

4. Salin file `.env.example` menjadi `.env` dan konfigurasikan database Anda:
   ```bash
   cp .env.example .env
   ```

5. Hasilkan (generate) Application Key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan *seeder* untuk mengisi data awal:
   ```bash
   php artisan migrate --seed
   ```

7. *Build* aset statis (TailwindCSS):
   ```bash
   npm run build
   ```

8. Jalankan *local development server*:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://localhost:8000`.

## Penggunaan

- **Halaman Utama (Publik)**: Akses root URL `/` untuk melihat dashboard statistik layanan.
- **Halaman Admin**: Akses `/admin` untuk masuk ke halaman panel pengelola data. (Pastikan rute diamankan dengan *middleware* autentikasi jika diaplikasikan pada produksi).

## Teknologi yang Digunakan

- [Laravel](https://laravel.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Chart.js](https://www.chartjs.org/)

## Lisensi

Aplikasi ini bersifat *open-source* berdasarkan lisensi [MIT license](https://opensource.org/licenses/MIT).
