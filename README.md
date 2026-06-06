# SIMINES - Sistem Informasi Minuman Es Terintegrasi

**SIMINES** adalah platform E-Commerce berbasis Web & PWA yang dirancang khusus untuk digitalisasi UMKM minuman es di Waingapu, Sumba Timur. Sistem ini mengintegrasikan teknologi pemetaan modern untuk memberikan pengalaman belanja yang transparan dan efisien.

## 🚀 Fitur Unggulan

### 📍 Teknologi Peta & Pengiriman (Leaflet.js)
- **Ongkir Otomatis Berbasis Jarak**: Kalkulasi biaya pengiriman secara real-time menggunakan koordinat GPS (Haversine Formula).
- **Titik Lokasi Presisi**: Pelanggan dapat menentukan lokasi pengantaran dengan menggeser pin di peta interaktif.
- **Reverse Geocoding**: Konversi otomatis titik koordinat menjadi teks alamat lengkap (OpenStreetMap).
- **Batasan Radius Layanan**: Admin dapat mengatur radius maksimal pengiriman yang dapat dilayani oleh toko.

### 📱 Pengalaman Pengguna (UX/PWA)
- **Progressive Web App (PWA)**: Dapat diinstal langsung di HP Android/iOS layaknya aplikasi mobile asli tanpa melalui Play Store.
- **Mode Gelap (Dark Mode)**: Mendukung tema gelap yang persisten (tersimpan di browser).
- **Tracking Pesanan**: Visual progres pesanan real-time (Pesan -> Dibayar -> Pembuatan -> Pengiriman -> Selesai).
- **Fitur Reservasi**: Pelanggan dapat menjadwalkan pesanan untuk jam/hari tertentu, bahkan saat toko sedang tutup.

### 💼 Manajemen & Bisnis (Admin Panel)
- **Dashboard Analitik**: Grafik tren penjualan 7 hari terakhir (Chart.js) dan laporan keuntungan bersih (Produk + Ongkir).
- **Ekspor Laporan PDF**: Cetak laporan transaksi resmi untuk arsip bisnis.
- **Manajemen Promo & Banner**: Kelola slider gambar promo di beranda secara dinamis.
- **Sistem Voucher & Diskon**: Manajemen kode voucher dengan syarat minimal belanja.
- **Alert Stok Menipis**: Peringatan visual otomatis untuk produk yang stoknya hampir habis.

## 🛠️ Stack Teknologi
- **Backend**: Laravel 11/12
- **Frontend**: Bootstrap 5, Blade Engine
- **Peta**: Leaflet.js & OpenStreetMap (Nominatim)
- **Grafik**: Chart.js
- **PDF**: Laravel DomPDF
- **Infrastructure**: Docker Ready (PHP 8.5 Alpine)

## 📦 Instalasi (Lokal)

1. **Clone Repositori**
   ```bash
   git clone https://github.com/arifrenggy404-dev/SIMINES.git
   cd SIMINES
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeder**
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## 🐳 Docker Deployment
Aplikasi ini sudah menyertakan `Dockerfile`. Untuk menjalankan via Docker:
```bash
docker build -t simines-app .
docker run -p 8080:8080 simines-app
```

---
**Dibuat oleh Kelompok 9 - Universitas Kristen Wira Wacana Sumba**
*Proyek Akhir UAS Sistem Informasi*
