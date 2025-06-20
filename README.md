# 🌐 Website Artikel - Your Daily Need Articles

Ini adalah proyek website artikel dinamis yang dibangun menggunakan PHP native dan database MySQL. Proyek ini berfungsi sebagai platform bagi para penulis untuk mempublikasikan tulisan mereka dan bagi pembaca untuk menemukan inspirasi harian.

## ✨ Fitur Utama

* **Sistem Autentikasi:** Proses login untuk penulis dengan role 'admin' dan 'user'.
* **Manajemen Konten (CRUD):** Admin dapat menambah, melihat, mengedit, dan menghapus artikel, kategori, serta mengelola data penulis melalui dashboard khusus.
* **Halaman Dinamis:** Halaman utama menampilkan artikel terbaru dengan layout yang menarik dan responsif.
* **Penyortiran & Pencarian:** Pengguna dapat melihat artikel berdasarkan kategori dan melakukan pencarian berdasarkan judul atau isi.
* **Interaksi Pengguna:** Pengguna yang sudah login dapat berinteraksi dengan meninggalkan komentar di setiap artikel.
* **Profil Pengguna:** Setiap penulis memiliki halaman profil pribadi dan halaman untuk melihat daftar artikel yang telah mereka publikasikan.

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL
* **Framework CSS:** Bootstrap 5
* **Editor Teks:** CKEditor 5
* **Server Lokal:** Laragon


## 🚀 Cara Menjalankan Proyek Secara Lokal

1.  Pastikan Anda memiliki server lokal seperti Laragon atau XAMPP yang sudah terinstall.
2.  Clone repository ini ke komputer Anda.
    ```bash
    git clone [https://github.com/NamaAnda/project-pemweb.git](https://github.com/NamaAnda/project-pemweb.git)
    ```
3.  Letakkan folder proyek di dalam direktori web server Anda (misal: `D:\laragon\www\`).
4.  Buat sebuah database baru di MySQL/phpMyAdmin dengan nama `webpage`.
5.  Impor file `.sql` yang berisi struktur tabel ke dalam database `webpage` Anda.
6.  Sesuaikan detail koneksi database (nama user, password) di dalam file `db.php`.
7.  Akses proyek melalui browser Anda (misal: `http://project-pemweb.test:8081`).

---
