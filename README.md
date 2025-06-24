# 🌐 Website Artikel - Your Daily Need Articles

Selamat datang di repository Website Artikel! Ini adalah proyek aplikasi web dinamis yang dibangun menggunakan PHP native dan database MySQL. Proyek ini berfungsi sebagai platform modern bagi para penulis untuk mempublikasikan tulisan mereka dan bagi pembaca untuk menemukan inspirasi harian dengan pengalaman pengguna yang interaktif.

## ✨ Fitur Utama

Proyek ini tidak hanya sebatas website biasa, tapi dilengkapi dengan berbagai fitur modern untuk meningkatkan pengalaman pengguna dan kemudahan manajemen.

#### 👨‍👩‍👧‍👦 Fitur Untuk Pengguna & Penulis
* **Sistem Autentikasi:** Proses login yang aman untuk penulis dengan dua level hak akses: **Admin** dan **User**.
* **Halaman Profil:** Setiap penulis memiliki halaman profil pribadi untuk mengelola informasi akun, termasuk mengubah nama, email, dan password.
* **Manajemen Artikel Pribadi:** Penulis dapat melihat daftar semua artikel yang telah mereka publikasikan di halaman "Artikel Saya".
* **Sistem Komentar:** Pengguna yang telah login dapat berinteraksi dan berdiskusi melalui kolom komentar di setiap artikel.
* **🔖 Fitur Bookmark:** Pengguna dapat menyimpan artikel yang mereka sukai untuk dibaca nanti dan melihatnya kembali di halaman "Artikel Disimpan".

#### ⚙️ Fitur Untuk Admin
* **Dashboard Terpusat:** Halaman admin dengan sistem tab untuk mengelola semua aspek website di satu tempat.
* **Manajemen Konten (CRUD):** Admin memiliki hak akses penuh untuk Menambah, Membaca, Mengedit, dan Menghapus (CRUD) data **Artikel**, **Kategori**, dan **Penulis**.
* **Editor Teks Modern:** Menggunakan **CKEditor 5**, sebuah WYSIWYG editor modern untuk memudahkan penulisan dan pemformatan isi artikel.

#### 🎨 Desain & Pengalaman Pengguna
* **Desain Kustom "Glossy Pastel":** Tampilan website yang unik, modern, dan konsisten di semua halaman, menggunakan efek *neumorphism* yang lembut.
* **Layout Responsif:** Dibangun dengan Bootstrap 5, tampilan website dapat menyesuaikan diri dengan baik di berbagai ukuran layar, dari desktop hingga mobile.
* **Penemuan Konten:**
    * **Pencarian:** Fitur pencarian berdasarkan judul atau isi artikel.
    * **Filter Kategori:** Halaman khusus untuk menampilkan artikel berdasarkan kategori.
    * **Sistem Tagging:** Setiap artikel dapat memiliki banyak tag, dan pengguna bisa mengklik tag untuk menemukan artikel dengan topik serupa.
    * **Artikel Terkait:** Sidebar cerdas yang menampilkan artikel lain dari kategori yang sama.

## 🚀 Live Demo

Penasaran dengan hasilnya? Anda bisa mengakses versi live dari website ini di:

**[yourdailyneedarticles.infinityfreeapp.com](http://yourdailyneedarticles.infinityfreeapp.com/)**



## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP 8+
* **Frontend:** HTML5, CSS3, JavaScript
* **Database:** MySQL (dikelola via phpMyAdmin)
* **Framework CSS:** Bootstrap 5
* **Editor Teks:** CKEditor 5
* **Server Lokal:** Laragon
* **Version Control:** Git & GitHub
* **Hosting:** InfinityFree

## 📖 Cara Menjalankan Proyek Secara Lokal

1.  Pastikan Anda memiliki server lokal seperti Laragon atau XAMPP yang sudah terinstall.
2.  Clone repository ini ke komputer Anda:
    ```bash
    git clone [https://github.com/ndilashfr/website-artikel.git](https://github.com/ndilashfr/website-artikel.git)
    ```
3.  Letakkan folder proyek di dalam direktori web server Anda (misal: `D:\laragon\www\`).
4.  Buat sebuah database baru di MySQL/phpMyAdmin dengan nama `webpage`.
5.  Impor file `database_backup.sql` yang tersedia di repository ke dalam database `webpage` Anda.
6.  Sesuaikan detail koneksi database (host, user, pass, db name) di dalam file `db.php`.
7.  Akses proyek melalui browser Anda (misal: `http://project-pemweb.test:8081`).

---
_Proyek ini merupakan hasil dari proses belajar, eksplorasi, dan debugging intensif dalam Pemrograman Web._
