# Aplikasi Inventaris Kendaraan BMD

# Pembuatan agents.MD
Membuat MD menggunakan :
Untuk jenis AI dan kemampuan agentic:
Aspek	Keterangan
Model	GPT-6
Platform	ChatGPT dari OpenAI

## Tentang Project

Aplikasi Inventaris Kendaraan BMD merupakan aplikasi berbasis web yang dikembangkan untuk membantu pengelolaan dan pencatatan Barang Milik Daerah (BMD), khususnya kendaraan dinas.

Project ini dikembangkan dengan pendekatan **AI-Assisted Development** menggunakan Claude sebagai AI coding agent yang membantu proses analisis kebutuhan, perancangan sistem, pembuatan kode, pengembangan fitur, debugging, dan penyempurnaan aplikasi.

## AI & Development Tools

Pengembangan aplikasi ini memanfaatkan teknologi dan tools berikut:

| Tools | Keterangan |
|---|---|
| Claude Agentic Sonnet 5.5 | AI coding agent untuk membantu proses pengembangan aplikasi |
| Visual Studio Code | Code editor utama |
| XAMPP | Local development environment |
| PHP 8.2+ | Backend programming language |
| Laravel 12 | PHP web application framework |
| MySQL 8 | Relational database management system |
| Bootstrap 5 | Frontend framework |
| jQuery | JavaScript library |
| Laravel Blade | Template engine |
| SweetAlert2 | Notifikasi dan konfirmasi interaktif |

## AI-Assisted Development Workflow

Proses pengembangan aplikasi menggunakan pendekatan agentic AI dengan Claude yang terintegrasi melalui Visual Studio Code.

Claude digunakan untuk membantu beberapa tahapan berikut:

1. **Requirement Analysis**  
   Menganalisis kebutuhan sistem, memahami proses bisnis, dan menerjemahkan kebutuhan menjadi fitur aplikasi.

2. **System Design**  
   Membantu merancang struktur database, relasi tabel, alur proses, dan arsitektur aplikasi.

3. **Code Generation**  
   Membantu membuat controller, model jika diperlukan, migration, seeder, Blade view, serta query database.

4. **Feature Development**  
   Membantu mengembangkan fitur CRUD, validasi input, pencarian, filter, dashboard, dan integrasi AJAX.

5. **Debugging & Troubleshooting**  
   Membantu mengidentifikasi kesalahan kode, memperbaiki error, dan mengoptimalkan query database.

6. **Code Review & Optimization**  
   Membantu meninjau konsistensi kode, keamanan dasar, performa query, dan maintainability aplikasi.

**Catatan:** Claude berperan sebagai asisten pengembangan berbasis AI. Implementasi, pengujian, dan validasi akhir tetap dilakukan oleh developer.

## Fitur Aplikasi

- Dashboard inventaris kendaraan.
- Manajemen data kendaraan dinas.
- Manajemen data pegawai PNS.
- Manajemen data Perangkat Daerah.
- Relasi data kendaraan dengan pegawai dan Perangkat Daerah.
- Pencarian dan filter data.
- Create, Read, Update, Delete (CRUD).
- Autentikasi dan pengelolaan pengguna.

## Development Environment

### 1. Visual Studio Code

Visual Studio Code digunakan sebagai editor utama untuk mengelola source code aplikasi.

### 2. Claude Agentic Sonnet 5.5

Claude digunakan melalui lingkungan pengembangan Visual Studio Code untuk membantu proses coding, analisis, debugging, dan pengembangan fitur secara agentic.

### 3. XAMPP

XAMPP digunakan sebagai server lokal untuk menjalankan Apache dan MySQL selama proses pengembangan dan pengujian aplikasi.

### 4. phpMyAdmin

phpMyAdmin digunakan untuk mengelola database MySQL, termasuk membuat database, import SQL, menjalankan query, dan memeriksa data.

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru.
- Composer.
- XAMPP.
- MySQL 8.
- Visual Studio Code.
- Claude Code atau integrasi Claude yang digunakan.
- Node.js dan NPM.

## Instalasi Project

### 1. Clone Repository

```bash
git clone <URL_REPOSITORY>
cd inventaris-kendaraan-bmd
```

### 2. Install Dependency

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Jalankan XAMPP, kemudian aktifkan MySQL.

Buka phpMyAdmin:

http://localhost/phpmyadmin

lalu import db nya

### 5. Migrasi Database

Jika menggunakan Laravel Migration:

```bash
php artisan migrate
```

Jika database sudah tersedia dalam bentuk file SQL, lakukan import melalui phpMyAdmin.

### 6. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jika menggunakan Vite:

```bash
npm run dev
```

Buka aplikasi melalui browser:

http://127.0.0.1:8000

## Struktur Teknologi

```text
inventaris-kendaraan-bmd/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── public/
├── .env.example
├── AGENTS.md
└── README.md
```

## Prinsip Pengembangan

- Menggunakan Laravel sebagai framework backend.
- Menggunakan Laravel Query Builder (`DB::table()`) untuk akses database.
- Menggunakan Bootstrap 5 untuk antarmuka.
- Menggunakan jQuery AJAX untuk komunikasi asynchronous.
- Menggunakan Laravel Blade sebagai template engine.
- Menjaga struktur kode agar mudah dipelihara dan dikembangkan.
- Memanfaatkan Claude AI untuk mempercepat proses pengembangan tanpa mengabaikan validasi developer.

## Informasi Project

| Informasi | Detail |
|---|---|
| Nama Project | Inventaris Kendaraan BMD |
| Framework | Laravel 12 |
| Database | MySQL 8 |
| AI Coding Agent | Claude Agentic Sonnet 5.5 |
| Code Editor | Visual Studio Code |
| Local Server | XAMPP |
| Versi | 1.0.0 |
| Status | Development |

---

**Developed with AI-Assisted Development Workflow**

*Built using Laravel, MySQL, Visual Studio Code, XAMPP, and Claude AI.*