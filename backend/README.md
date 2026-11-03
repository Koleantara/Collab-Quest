# Evaluasi Proyek - Peran A (Backend)

### 1. Apa perbedaan antara mock data dan API nyata?
- **Mock Data:** Data buatan (bohongan) yang ditulis statis oleh tim Frontend di awal proyek agar mereka bisa mendesain tampilan tanpa menunggu Backend selesai ngoding. Data ini tidak tersimpan ke database lokal.
- **API Nyata:** Jembatan data asli yang terhubung ke database lokal (MySQL Laragon). Data yang diinputkan benar-benar tersimpan, divalidasi keamanannya, dan dikunci menggunakan token acak (Laravel Sanctum).

### 2. Perbedaan kontrak apa yang kalian temukan, dan bagaimana menyelesaikannya?
- **Kendala Kontrak:** Terjadi penyesuaian rute pengamanan token. Di awal, server Laravel mencoba mengalihkan (redirect) tamu tanpa token ke halaman web login biasa (`Route [login] not defined`).
- **Solusi Penyelesaian:** Tim Backend memperbarui konfigurasi proteksi pada file `bootstrap/app.php` dengan memaksa Laravel untuk selalu merespon menggunakan format JSON `401 Unauthorized` alih-alih melempar halaman web, sehingga aplikasi Frontend dapat membaca respon penolakan dengan benar.
