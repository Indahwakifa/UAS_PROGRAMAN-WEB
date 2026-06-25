# UAS_PROGRAMAN-WEB

# 📰 FikahNews - Portal Berita Berbasis Web

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-red)
![PHP](https://img.shields.io/badge/PHP-8+-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)

## 👤 Identitas Mahasiswa

| Keterangan | Data |
|------------|------|
| Nama | Indah Wafikah |
| NIM | 312410559 |
| Kelas | I241E |
| Mata Kuliah | Pemrograman Web 2 |

---

## 📖 Deskripsi Proyek

**FikahNews** merupakan aplikasi portal berita berbasis web yang dikembangkan menggunakan Framework **CodeIgniter 4** dan database **MySQL**.

Aplikasi ini memungkinkan pengguna untuk membaca artikel berita secara online, melakukan pencarian artikel, serta menyediakan halaman administrator untuk mengelola data artikel secara dinamis.

---

## 🎯 Tujuan Pengembangan

- Menerapkan konsep MVC menggunakan CodeIgniter 4.
- Mengimplementasikan CRUD (Create, Read, Update, Delete).
- Mengimplementasikan AJAX pada pengelolaan data.
- Mengelola artikel berita secara digital.
- Menyediakan portal informasi berbasis web yang responsif.

---

## 🚀 Teknologi yang Digunakan

| Teknologi | Fungsi |
|------------|---------|
| PHP | Bahasa Pemrograman |
| CodeIgniter 4 | Framework Backend |
| MySQL | Database |
| Bootstrap 5 | User Interface |
| JavaScript | Interaktivitas |
| AJAX | Asynchronous Request |
| HTML5 | Struktur Halaman |
| CSS3 | Styling |
| XAMPP | Web Server |
| GitHub | Version Control |

---

# 📌 Fitur Utama

### 🏠 Halaman Home

- Menampilkan informasi portal berita
- Menampilkan artikel terbaru
- Menampilkan informasi kampus
- Banner utama website

### 📄 Manajemen Artikel

- Tambah Artikel
- Edit Artikel
- Hapus Artikel
- Detail Artikel

### 🔍 Pencarian Artikel

Pengguna dapat mencari artikel berdasarkan kata kunci tertentu.

### ⚡ AJAX Management

- Menampilkan data artikel secara dinamis
- Refresh data tanpa reload halaman
- CRUD berbasis AJAX

### 🖼 Upload Gambar Artikel

Admin dapat menambahkan gambar pada artikel yang dipublikasikan.

### 📋 Artikel Terbaru

Menampilkan daftar artikel terbaru pada sidebar.

---

# 🔄 Flow Sistem

```text
Pengguna
    │
    ▼
Halaman Home
    │
    ├── Melihat Artikel
    ├── Mencari Artikel
    │
    ▼
Login Admin
    │
    ▼
Dashboard
    │
    ├── Tambah Artikel
    ├── Edit Artikel
    ├── Hapus Artikel
    │
    ▼
Database MySQL
```

---

# 👥 Use Case

## Pengunjung

- Melihat Home
- Membaca Artikel
- Mencari Artikel

## Admin

- Login
- Mengelola Artikel
- Upload Gambar
- Menghapus Artikel
- Mengedit Artikel
- Logout

---

# 🗄️ Struktur Database

## Tabel User

| Field | Tipe |
|---------|---------|
| id | INT |
| username | VARCHAR |
| password | VARCHAR |

---

## Tabel Artikel

| Field | Tipe |
|---------|---------|
| id | INT |
| judul | VARCHAR |
| isi | TEXT |
| gambar | VARCHAR |
| slug | VARCHAR |
| status | ENUM |

---

## Tabel Kategori

| Field | Tipe |
|---------|---------|
| id_kategori | INT |
| nama_kategori | VARCHAR |

---

# 🔗 Relasi Database

```text
Kategori (1)
      │
      │
      └──── Artikel (N)
```

---

# 📸 Screenshot Aplikasi

## 🏠 Halaman Home

![Home](images/home.png)

---

## 📄 Halaman Artikel

![Artikel](images/artikel.png)

---

## ⚡ Halaman AJAX

![Ajax](images/ajax.png)

---

## 📊 Dashboard Admin

![Dashboard](images/dashboard.png)

---

# 📂 Struktur Folder

```text
app/
├── Controllers/
├── Models/
├── Views/
├── Filters/
├── Config/

public/
├── assets/
├── uploads/

writable/
.env
```

---

# ⚙️ Cara Menjalankan Aplikasi

## 1. Clone Repository

```bash
git clone https://github.com/username/fikahnews.git
```

## 2. Masuk ke Folder Project

```bash
cd fikahnews
```

## 3. Install Dependency

```bash
composer install
```

## 4. Konfigurasi Database

Buat database baru pada MySQL kemudian sesuaikan file `.env`.

```env
database.default.hostname = localhost
database.default.database = db_fikahnews
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

## 5. Jalankan Server

```bash
php spark serve
```

Akses aplikasi:

```text
http://localhost:8080
```

---

# ✅ Hasil Implementasi

- [x] Login Authentication
- [x] CRUD Artikel
- [x] Upload Gambar
- [x] Pencarian Artikel
- [x] AJAX CRUD
- [x] Responsive Design
- [x] Sidebar Artikel Terbaru
- [x] Database MySQL

---

# 🎓 Kesimpulan

FikahNews berhasil dikembangkan sebagai portal berita berbasis web menggunakan Framework CodeIgniter 4 dan MySQL. Sistem mampu mengelola artikel secara dinamis, menyediakan fitur pencarian artikel, upload gambar, dan implementasi AJAX untuk meningkatkan pengalaman pengguna.

---

## 📚 Referensi

- CodeIgniter 4 Documentation
- PHP Documentation
- MySQL Documentation
- Bootstrap Documentation
- AJAX Documentation

---

⭐ Project UAS Pemrograman Web 2 - Universitas Pelita Bangsa
