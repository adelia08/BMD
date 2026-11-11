# AGENTS.md
# APLIKASI INVENTARIS KENDARAAN DINAS OPERASIONAL BMD

## 1. DESKRIPSI APLIKASI

Buat aplikasi web untuk pengelolaan dan inventarisasi Kendaraan Dinas Operasional milik Perangkat Daerah sebagai bagian dari pengelolaan Barang Milik Daerah (BMD).

Aplikasi bertujuan membantu pemerintah daerah dalam melakukan pendataan, pengelolaan, pemantauan, dan pemutakhiran data kendaraan dinas operasional yang digunakan oleh masing-masing Perangkat Daerah.

Aplikasi harus memiliki antarmuka yang sederhana, modern, responsif, mudah digunakan, dan memiliki struktur kode yang rapi.

### Tujuan utama
1. Mendata seluruh kendaraan dinas operasional milik Perangkat Daerah.
2. Mendata pegawai PNS yang menggunakan atau bertanggung jawab atas kendaraan dinas.
3. Mengelompokkan kendaraan berdasarkan Perangkat Daerah.
4. Memantau kondisi kendaraan dinas.
5. Mempermudah proses penambahan, perubahan, penghapusan, dan pencarian data kendaraan.
6. Menyediakan informasi inventaris kendaraan secara terpusat.

---

## 2. TEKNOLOGI YANG WAJIB DIGUNAKAN

Gunakan teknologi berikut:

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Bahasa Backend | PHP 8.2+ |
| Database | MySQL 8 |
| Database Access | Laravel Query Builder (DB Facade) |
| Frontend | HTML5, CSS3 |
| CSS Framework | Bootstrap 5 |
| JavaScript | JavaScript / jQuery |
| AJAX | jQuery AJAX |
| Template | Blade Laravel |
| Icon | Bootstrap Icons |
| Notifikasi | SweetAlert2 |
| Data Table | DataTables (opsional) |
| Web Server | Apache / Nginx |

### Ketentuan penting

- Semua operasi database menggunakan Laravel Query Builder.
- Gunakan `DB::table()` untuk SELECT, INSERT, UPDATE, DELETE, JOIN, dan transaksi.
- Jangan menggunakan Eloquent ORM untuk operasi database.
- Jangan menggunakan relasi Eloquent seperti `belongsTo()`, `hasMany()`, dan sebagainya.
- Gunakan Bootstrap 5 untuk seluruh komponen antarmuka.
- Gunakan AJAX untuk proses CRUD tanpa reload halaman.
- Gunakan Blade sebagai template frontend.
- Gunakan validasi backend Laravel.
- Gunakan CSRF Protection pada seluruh request AJAX yang membutuhkan autentikasi CSRF.
- Pisahkan Controller, View, Routes, dan JavaScript.
- Hindari penulisan kode yang terlalu kompleks dalam satu file.

---

## 3. MODUL APLIKASI

Aplikasi memiliki tiga modul data utama.

### A. DATA KENDARAAN DINAS OPERASIONAL

Field yang harus tersedia:

| No | Field | Tipe Data | Keterangan |
|---|---|---|---|
| 1 | ID | BIGINT | Primary Key |
| 2 | NOPOL | VARCHAR(20) | Nomor polisi kendaraan |
| 3 | MEREK | VARCHAR(100) | Merek kendaraan |
| 4 | TIPE_MODEL | VARCHAR(150) | Tipe atau model kendaraan |
| 5 | TAHUN_PRODUKSI | YEAR | Tahun produksi |
| 6 | WARNA | VARCHAR(50) | Warna kendaraan |
| 7 | KONDISI | VARCHAR(30) | Kondisi kendaraan |
| 8 | PERANGKAT_DAERAH_ID | BIGINT | Relasi Perangkat Daerah |
| 9 | PEGAWAI_ID | BIGINT | Relasi PNS/Pengguna kendaraan |
| 10 | CREATED_AT | TIMESTAMP | Waktu pembuatan |
| 11 | UPDATED_AT | TIMESTAMP | Waktu perubahan |

Pilihan kondisi kendaraan:

- BAIK
- RUSAK RINGAN
- RUSAK BERAT

Ketentuan:
- NOPOL wajib diisi.
- NOPOL harus unik.
- Merek wajib diisi.
- Tipe/model wajib diisi.
- Tahun produksi harus valid.
- Kondisi kendaraan wajib dipilih.
- Perangkat Daerah wajib dipilih.
- PNS yang menggunakan kendaraan boleh kosong jika kendaraan belum memiliki pengguna.
- Data kendaraan harus dapat ditampilkan berdasarkan Perangkat Daerah.

### B. DATA PEGAWAI / PNS

Field:

| No | Field | Tipe Data | Keterangan |
|---|---|---|---|
| 1 | ID | BIGINT | Primary Key |
| 2 | NIP | VARCHAR(30) | Nomor Induk Pegawai |
| 3 | NAMA | VARCHAR(150) | Nama pegawai |
| 4 | JABATAN | VARCHAR(150) | Jabatan pegawai |
| 5 | GOLONGAN | VARCHAR(20) | Golongan pegawai |
| 6 | PERANGKAT_DAERAH_ID | BIGINT | Perangkat Daerah |
| 7 | CREATED_AT | TIMESTAMP | Waktu pembuatan |
| 8 | UPDATED_AT | TIMESTAMP | Waktu perubahan |

Ketentuan:
- NIP wajib diisi dan unik.
- Nama pegawai wajib diisi.
- Jabatan wajib diisi.
- Golongan wajib diisi.
- Setiap pegawai terdaftar pada satu Perangkat Daerah.
- Data pegawai dapat digunakan sebagai referensi pengguna kendaraan.

### C. DATA PERANGKAT DAERAH

Data awal:

1. Dinas Pendidikan
2. Dinas Kesehatan
3. Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)

Field:

| No | Field | Tipe Data |
|---|---|---|
| 1 | ID | BIGINT |
| 2 | KODE | VARCHAR(30) |
| 3 | NAMA_PERANGKAT_DAERAH | VARCHAR(150) |
| 4 | CREATED_AT | TIMESTAMP |
| 5 | UPDATED_AT | TIMESTAMP |

Ketentuan:
- Kode Perangkat Daerah harus unik.
- Nama Perangkat Daerah wajib diisi.
- Data Perangkat Daerah dapat ditambah, diedit, dan dihapus.
- Perangkat Daerah yang masih memiliki data kendaraan atau pegawai tidak boleh dihapus.

---

## 4. RELASI DATABASE

Gunakan struktur relasi berikut:

PERANGKAT_DAERAH
- memiliki banyak PEGAWAI.
- memiliki banyak KENDARAAN.

PEGAWAI
- terdaftar pada satu PERANGKAT_DAERAH.
- dapat menjadi pengguna kendaraan.

KENDARAAN
- terdaftar pada satu PERANGKAT_DAERAH.
- dapat memiliki satu PEGAWAI sebagai pengguna atau penanggung jawab.

Relasi database:

    perangkat_daerah.id
        |
        |--- pegawai.perangkat_daerah_id
        |
        |--- kendaraan.perangkat_daerah_id

    pegawai.id
        |
        |--- kendaraan.pegawai_id

Pastikan kendaraan dan pegawai yang dipilih berasal dari Perangkat Daerah yang sama.

---

## 5. DATABASE MYSQL

Buat database dengan nama:

    db_inventaris_kendaraan

Gunakan migration Laravel untuk membuat tabel berikut:

1. perangkat_daerah
2. pegawai
3. kendaraan
4. users (untuk autentikasi administrator)

### Struktur tabel perangkat_daerah

- id: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- kode: VARCHAR(30) UNIQUE
- nama: VARCHAR(150)
- timestamps

### Struktur tabel pegawai

- id: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- nip: VARCHAR(30) UNIQUE
- nama: VARCHAR(150)
- jabatan: VARCHAR(150)
- golongan: VARCHAR(20)
- perangkat_daerah_id: BIGINT UNSIGNED
- timestamps

Foreign key:
- perangkat_daerah_id references perangkat_daerah.id

### Struktur tabel kendaraan

- id: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- nopol: VARCHAR(20) UNIQUE
- merek: VARCHAR(100)
- tipe_model: VARCHAR(150)
- tahun_produksi: YEAR
- warna: VARCHAR(50)
- kondisi: ENUM('BAIK','RUSAK RINGAN','RUSAK BERAT')
- perangkat_daerah_id: BIGINT UNSIGNED
- pegawai_id: BIGINT UNSIGNED NULLABLE
- timestamps

Foreign key:
- perangkat_daerah_id references perangkat_daerah.id
- pegawai_id references pegawai.id

Gunakan foreign key dengan aturan RESTRICT untuk mencegah penghapusan data induk yang masih digunakan. Untuk pegawai yang dihapus, kendaraan dapat terlebih dahulu dilepas penanggung jawabnya melalui proses yang terkontrol.

### Seeder

Buat Database Seeder untuk memasukkan data awal Perangkat Daerah:

| Kode | Nama |
|---|---|
| 001 | DINAS PENDIDIKAN |
| 002 | DINAS KESEHATAN |
| 003 | DINAS PEKERJAAN UMUM DAN PENATAAN RUANG |

Buat satu akun administrator untuk pengujian aplikasi.

Password harus menggunakan Hash Laravel, bukan plaintext.

---

## 6. FLOW APLIKASI

### FLOW 1 - DASHBOARD

Ketika pengguna berhasil masuk, tampilkan dashboard dengan informasi:

1. Total seluruh kendaraan.
2. Total kendaraan kondisi BAIK.
3. Total kendaraan RUSAK RINGAN.
4. Total kendaraan RUSAK BERAT.
5. Total Perangkat Daerah.
6. Total pegawai terdaftar.

Dashboard juga menampilkan:
- Grafik jumlah kendaraan berdasarkan kondisi.
- Grafik jumlah kendaraan berdasarkan Perangkat Daerah.
- Tabel kendaraan terbaru.

Seluruh informasi dashboard harus berasal dari database MySQL menggunakan Query Builder.

### FLOW 2 - DATA KENDARAAN

Menu: Inventaris Kendaraan

Tampilkan tabel kendaraan dengan kolom:

| No | Nopol | Merek | Tipe/Model | Tahun | Warna | Kondisi | Perangkat Daerah | Pengguna | Aksi |
|---|---|---|---|---|---|---|---|---|---|

Fitur:
- Tambah kendaraan.
- Edit kendaraan.
- Hapus kendaraan.
- Detail kendaraan.
- Pencarian berdasarkan NOPOL.
- Filter berdasarkan Perangkat Daerah.
- Filter berdasarkan kondisi kendaraan.
- Pagination.
- Sorting tabel.

Form tambah dan edit menggunakan Bootstrap Modal.

Ketika menambahkan kendaraan, pengguna harus memilih:
1. Perangkat Daerah.
2. Pegawai pengguna kendaraan (opsional).
3. NOPOL.
4. Merek kendaraan.
5. Tipe/model kendaraan.
6. Tahun produksi.
7. Warna.
8. Kondisi kendaraan.

Ketika Perangkat Daerah dipilih, daftar pegawai harus menyesuaikan Perangkat Daerah tersebut.

Gunakan AJAX untuk mengambil daftar pegawai berdasarkan perangkat daerah.

Jika Perangkat Daerah berubah, kosongkan pilihan pegawai sebelumnya.

### FLOW 3 - DATA PEGAWAI

Menu: Data Pegawai

Tampilkan tabel:

| No | NIP | Nama Pegawai | Jabatan | Golongan | Perangkat Daerah | Aksi |
|---|---|---|---|---|---|---|

Fitur:
- Tambah pegawai.
- Edit pegawai.
- Hapus pegawai.
- Detail pegawai.
- Pencarian berdasarkan NIP dan nama.
- Filter berdasarkan Perangkat Daerah.
- Pagination.

Gunakan AJAX untuk seluruh proses CRUD.

Validasi:
- NIP tidak boleh duplikat.
- Seluruh field wajib diisi.
- Pegawai yang masih digunakan sebagai penanggung jawab kendaraan tidak boleh langsung dihapus sebelum hubungan kendaraan diselesaikan.

### FLOW 4 - DATA PERANGKAT DAERAH

Menu: Data Perangkat Daerah

Tampilkan tabel:

| No | Kode | Nama Perangkat Daerah | Jumlah Kendaraan | Jumlah Pegawai | Aksi |
|---|---|---|---|---|---|

Fitur:
- Tambah Perangkat Daerah.
- Edit Perangkat Daerah.
- Hapus Perangkat Daerah.
- Detail Perangkat Daerah.

Jumlah kendaraan dan pegawai harus dihitung langsung menggunakan Query Builder.

Perangkat Daerah yang memiliki kendaraan atau pegawai tidak boleh dihapus.

### FLOW 5 - DETAIL INVENTARIS

Setiap kendaraan memiliki halaman atau modal detail yang menampilkan:

INFORMASI KENDARAAN
- NOPOL
- Merek
- Tipe/model
- Tahun produksi
- Warna
- Kondisi

INFORMASI PENGGUNA
- Nama pegawai
- NIP
- Jabatan
- Golongan

INFORMASI PERANGKAT DAERAH
- Nama Perangkat Daerah

Data ditampilkan menggunakan JOIN Query Builder.

---

## 7. STRUKTUR FOLDER PROJECT

Gunakan struktur Laravel yang terorganisir:

    app/
      Http/
        Controllers/
          AuthController.php
          DashboardController.php
          KendaraanController.php
          PegawaiController.php
          PerangkatDaerahController.php

    database/
      migrations/
      seeders/
        PerangkatDaerahSeeder.php
        AdminSeeder.php
        DatabaseSeeder.php

    resources/
      views/
        layouts/
          app.blade.php
          sidebar.blade.php
          navbar.blade.php

        auth/
          login.blade.php

        dashboard/
          index.blade.php

        kendaraan/
          index.blade.php
          detail.blade.php

        pegawai/
          index.blade.php

        perangkat-daerah/
          index.blade.php

      js/
        app.js
        kendaraan.js
        pegawai.js
        perangkat-daerah.js

    routes/
      web.php

    public/
      assets/

Pisahkan JavaScript per modul agar mudah dikelola.

---

## 8. ROUTING

Gunakan prefix route yang konsisten.

Contoh:

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
        Route::get('/', [KendaraanController::class, 'index'])->name('index');
        Route::get('/data', [KendaraanController::class, 'data'])->name('data');
        Route::post('/', [KendaraanController::class, 'store'])->name('store');
        Route::get('/{id}', [KendaraanController::class, 'show'])->name('show');
        Route::put('/{id}', [KendaraanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KendaraanController::class, 'destroy'])->name('destroy');
    });

Buat routing serupa untuk:
- Pegawai.
- Perangkat Daerah.

Tambahkan endpoint AJAX untuk:
- Mengambil daftar pegawai berdasarkan Perangkat Daerah.
- Mengambil detail kendaraan.
- Mengambil data dashboard.

Seluruh halaman aplikasi harus dilindungi middleware auth kecuali halaman login.

---

## 9. QUERY BUILDER

Wajib menggunakan Laravel Query Builder.

Contoh mengambil data kendaraan:

    $data = DB::table('kendaraan as k')
        ->leftJoin('pegawai as p', 'p.id', '=', 'k.pegawai_id')
        ->join('perangkat_daerah as pd', 'pd.id', '=', 'k.perangkat_daerah_id')
        ->select(
            'k.*',
            'p.nama as nama_pegawai',
            'p.nip',
            'p.jabatan',
            'p.golongan',
            'pd.nama as nama_perangkat_daerah'
        )
        ->orderBy('k.id', 'desc')
        ->get();

Contoh mengambil pegawai berdasarkan Perangkat Daerah:

    $pegawai = DB::table('pegawai')
        ->where('perangkat_daerah_id', $id)
        ->orderBy('nama')
        ->get();

Contoh menghitung jumlah kendaraan berdasarkan kondisi:

    $total = DB::table('kendaraan')
        ->select('kondisi', DB::raw('COUNT(*) as total'))
        ->groupBy('kondisi')
        ->get();

Contoh menyimpan data:

    DB::table('kendaraan')->insert([
        'nopol' => $request->nopol,
        'merek' => $request->merek,
        'tipe_model' => $request->tipe_model,
        'tahun_produksi' => $request->tahun_produksi,
        'warna' => $request->warna,
        'kondisi' => $request->kondisi,
        'perangkat_daerah_id' => $request->perangkat_daerah_id,
        'pegawai_id' => $request->pegawai_id,
        'created_at' => now(),
        'updated_at' => now()
    ]);

Contoh update:

    DB::table('kendaraan')
        ->where('id', $id)
        ->update([
            'nopol' => $request->nopol,
            'merek' => $request->merek,
            'tipe_model' => $request->tipe_model,
            'tahun_produksi' => $request->tahun_produksi,
            'warna' => $request->warna,
            'kondisi' => $request->kondisi,
            'perangkat_daerah_id' => $request->perangkat_daerah_id,
            'pegawai_id' => $request->pegawai_id,
            'updated_at' => now()
        ]);

Gunakan binding parameter bawaan Query Builder. Jangan menggabungkan input pengguna langsung ke dalam SQL mentah.

---

## 10. AJAX CRUD

Semua proses CRUD harus menggunakan AJAX.

Ketentuan:
- Tambah data tidak boleh reload halaman.
- Edit data tidak boleh reload halaman.
- Hapus data tidak boleh reload halaman.
- Validasi ditampilkan langsung pada form.
- Tampilkan loading ketika request berlangsung.
- Tampilkan notifikasi sukses dan gagal.
- Refresh DataTable setelah proses berhasil.

Contoh konfigurasi CSRF:

    <meta name="csrf-token" content="{{ csrf_token() }}">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

Contoh AJAX POST:

    $.ajax({
        url: '/kendaraan',
        type: 'POST',
        data: $('#formKendaraan').serialize(),
        success: function(response) {
            Swal.fire('Berhasil', response.message, 'success');
            $('#modalKendaraan').modal('hide');
            table.ajax.reload(null, false);
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                // Tampilkan pesan validasi di masing-masing field.
            } else {
                Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
            }
        }
    });

Gunakan HTTP method POST, PUT, dan DELETE sesuai kebutuhan. Untuk PUT dan DELETE melalui jQuery AJAX, gunakan method spoofing Laravel atau kirim request dengan method yang sesuai.

---

## 11. DESAIN UI/UX

Gunakan desain dashboard administrasi pemerintahan yang modern.

Ketentuan desain:

- Bootstrap 5.
- Responsive desktop, tablet, dan mobile.
- Sidebar navigasi.
- Navbar bagian atas.
- Dashboard dengan kartu statistik.
- Tabel dengan pagination.
- Modal untuk tambah dan edit data.
- SweetAlert2 untuk konfirmasi penghapusan.
- Badge warna untuk kondisi kendaraan.

Warna badge kondisi:

| Kondisi | Warna |
|---|---|
| BAIK | Hijau |
| RUSAK RINGAN | Kuning |
| RUSAK BERAT | Merah |

Menu sidebar:

1. Dashboard
2. Inventaris Kendaraan
3. Data Pegawai
4. Perangkat Daerah
5. Logout

Jangan menggunakan desain yang terlalu ramai. Prioritaskan kemudahan penggunaan dan keterbacaan data.

Gunakan komponen Bootstrap 5, bukan membuat CSS custom secara berlebihan.

---

## 12. INSTALASI DARI AWAL

### Persyaratan

Pastikan perangkat telah memiliki:

- PHP 8.2 atau lebih baru.
- Composer.
- MySQL 8.
- Node.js dan NPM.
- Web browser.

Periksa versi:

    php -v
    composer -V
    node -v
    npm -v

### STEP 1 - Membuat Project Laravel

Jalankan:

    composer create-project laravel/laravel inventaris-kendaraan "^12.0"

Masuk ke direktori:

    cd inventaris-kendaraan

Jalankan server:

    php artisan serve

Akses:

    http://127.0.0.1:8000

### STEP 2 - Membuat Database MySQL

Masuk ke MySQL:

    mysql -u root -p

Buat database:

    CREATE DATABASE db_inventaris_kendaraan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

Keluar dari MySQL:

    EXIT;

### STEP 3 - Konfigurasi Database Laravel

Buka file `.env`.

Atur konfigurasi:

    APP_NAME="Inventaris Kendaraan BMD"
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://127.0.0.1:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_inventaris_kendaraan
    DB_USERNAME=root
    DB_PASSWORD=

Sesuaikan DB_USERNAME dan DB_PASSWORD dengan akun MySQL yang digunakan.

Generate application key:

    php artisan key:generate

Bersihkan konfigurasi cache:

    php artisan config:clear

Uji koneksi database melalui:

    php artisan migrate:status

Jika berhasil, Laravel sudah dapat terhubung ke database MySQL.

### STEP 4 - Membuat Migration

Jalankan:

    php artisan make:migration create_perangkat_daerah_table
    php artisan make:migration create_pegawai_table
    php artisan make:migration create_kendaraan_table

Pastikan urutan migration mengikuti ketergantungan foreign key.

Jalankan:

    php artisan migrate

### STEP 5 - Membuat Seeder

Jalankan:

    php artisan make:seeder PerangkatDaerahSeeder
    php artisan make:seeder AdminSeeder

Isi data awal Perangkat Daerah dan akun administrator.

Jalankan:

    php artisan db:seed

### STEP 6 - Membuat Controller

Jalankan:

    php artisan make:controller AuthController
    php artisan make:controller DashboardController
    php artisan make:controller KendaraanController
    php artisan make:controller PegawaiController
    php artisan make:controller PerangkatDaerahController

Seluruh controller menggunakan Query Builder.

### STEP 7 - Membuat View

Buat seluruh file Blade berdasarkan struktur folder pada bagian 7.

Gunakan Bootstrap 5 dan jQuery untuk frontend.

Bootstrap dan jQuery dapat menggunakan CDN atau disiapkan melalui asset lokal.

### STEP 8 - Menjalankan Aplikasi

Jalankan:

    php artisan optimize:clear
    npm install
    npm run build
    php artisan serve

Buka:

    http://127.0.0.1:8000

Login menggunakan akun administrator yang dibuat melalui seeder.

---

## 13. KEAMANAN APLIKASI

Implementasikan:

1. Login dan logout.
2. Password menggunakan Hash Laravel.
3. CSRF Protection.
4. Validasi seluruh input.
5. Proteksi SQL Injection melalui Query Builder.
6. Proteksi XSS melalui escaping Blade.
7. Middleware auth.
8. Validasi ID sebelum update dan delete.
9. Validasi relasi kendaraan dengan pegawai dan Perangkat Daerah.
10. Gunakan DB::transaction() untuk proses yang melibatkan beberapa operasi database.
11. Jangan menampilkan detail error database kepada pengguna pada production.
12. Jangan menyimpan password dalam bentuk plaintext.

---

## 14. VALIDASI BISNIS

Aturan yang wajib diterapkan:

1. Nomor polisi kendaraan tidak boleh duplikat.
2. NIP pegawai tidak boleh duplikat.
3. Kendaraan harus memiliki Perangkat Daerah.
4. Pegawai harus memiliki Perangkat Daerah.
5. Pegawai yang dipilih sebagai pengguna kendaraan harus berasal dari Perangkat Daerah yang sama dengan kendaraan.
6. Kendaraan yang masih terdaftar tidak boleh kehilangan Perangkat Daerah.
7. Perangkat Daerah yang masih memiliki kendaraan atau pegawai tidak boleh dihapus.
8. Pegawai yang masih menjadi pengguna kendaraan tidak boleh dihapus sebelum hubungan kendaraan diperbarui.
9. Kondisi kendaraan hanya boleh BAIK, RUSAK RINGAN, atau RUSAK BERAT.
10. Tahun produksi harus berupa tahun yang valid.
11. Update dan delete harus memeriksa keberadaan data terlebih dahulu.

---

## 15. STANDAR PENULISAN KODE

AI Agent wajib mengikuti aturan berikut:

- Gunakan Laravel best practices.
- Gunakan Query Builder, bukan Eloquent.
- Gunakan DB Facade.
- Gunakan Controller untuk proses backend.
- Gunakan Blade untuk tampilan.
- Gunakan AJAX untuk interaksi CRUD.
- Gunakan Bootstrap 5.
- Gunakan nama variabel dan fungsi yang mudah dipahami.
- Hindari duplikasi kode.
- Jangan membuat satu controller terlalu besar.
- Pisahkan JavaScript sesuai modul.
- Gunakan response JSON yang konsisten.
- Pastikan semua route memiliki nama.
- Pastikan seluruh fitur dapat dijalankan.
- Jangan membuat tombol atau menu yang hanya menjadi dekorasi tanpa fungsi.
- Jangan menggunakan data dummy untuk menggantikan data database pada aplikasi utama.

### Format response JSON

Sukses:

    {
        "success": true,
        "message": "Data berhasil disimpan",
        "data": {}
    }

Gagal:

    {
        "success": false,
        "message": "Data gagal disimpan"
    }

Gunakan HTTP status code yang sesuai.

---

## 16. CHECKLIST SEBELUM PROJECT SELESAI

### Database
- [ ] Database MySQL berhasil dibuat.
- [ ] Migration berhasil dijalankan.
- [ ] Foreign key berfungsi.
- [ ] Seeder Perangkat Daerah berhasil.
- [ ] Akun administrator berhasil dibuat.
- [ ] Koneksi Laravel ke MySQL berhasil.

### Backend
- [ ] Login dan logout.
- [ ] Dashboard menggunakan data database.
- [ ] CRUD kendaraan.
- [ ] CRUD pegawai.
- [ ] CRUD Perangkat Daerah.
- [ ] Query Builder digunakan untuk seluruh operasi database.
- [ ] Validasi backend berjalan.
- [ ] Relasi antar data berjalan.
- [ ] Filter dan pencarian berjalan.

### Frontend
- [ ] Bootstrap 5 berjalan.
- [ ] Sidebar dan navbar berjalan.
- [ ] Dashboard responsif.
- [ ] Modal tambah dan edit berjalan.
- [ ] AJAX berjalan.
- [ ] SweetAlert2 berjalan.
- [ ] DataTable berjalan jika digunakan.
- [ ] Tidak ada reload halaman pada proses CRUD.

### Testing
- [ ] Tambah kendaraan.
- [ ] Edit kendaraan.
- [ ] Hapus kendaraan.
- [ ] Tambah pegawai.
- [ ] Edit pegawai.
- [ ] Hapus pegawai.
- [ ] Tambah Perangkat Daerah.
- [ ] Edit Perangkat Daerah.
- [ ] Hapus Perangkat Daerah.
- [ ] Validasi NOPOL duplikat.
- [ ] Validasi NIP duplikat.
- [ ] Validasi pengguna kendaraan lintas Perangkat Daerah.
- [ ] Dashboard menampilkan statistik yang benar.

---

## 17. INSTRUKSI UTAMA UNTUK AI CODING AGENT

Bangun aplikasi ini secara fullstack sampai benar-benar dapat digunakan.

Jangan hanya membuat desain frontend atau prototype.

Prioritaskan implementasi secara bertahap:

1. Setup project Laravel dan koneksi database MySQL.
2. Buat migration, seeder, dan struktur database.
3. Buat autentikasi administrator.
4. Buat layout utama Bootstrap 5.
5. Buat dashboard.
6. Buat CRUD Perangkat Daerah.
7. Buat CRUD Pegawai.
8. Buat CRUD Kendaraan.
9. Implementasikan relasi dan validasi antar data.
10. Implementasikan AJAX untuk seluruh CRUD.
11. Lakukan testing seluruh fitur.

Setiap fitur harus memiliki backend, frontend, validasi, dan koneksi database yang benar-benar berfungsi.

Sebelum membuat fitur baru, periksa struktur project yang sudah ada agar tidak menimpa konfigurasi atau kode yang sudah berjalan.

Jika terjadi error, lakukan analisis penyebab dan perbaiki sampai fitur berjalan.

**HASIL AKHIR YANG DIHARAPKAN:**

Aplikasi Inventaris Kendaraan Dinas Operasional BMD berbasis Laravel 12, MySQL, Bootstrap 5, dan AJAX yang dapat digunakan untuk mengelola data kendaraan, pegawai, dan Perangkat Daerah secara terintegrasi, dengan seluruh operasi database menggunakan Laravel Query Builder.
