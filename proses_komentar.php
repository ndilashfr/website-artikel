<?php
session_start();
include 'db.php';

// Pastikan user sudah login sebelum memproses
if (!isset($_SESSION['id_penulis'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Pastikan form disubmit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan session
    $id_artikel = (int)$_POST['id_artikel'];
    $isi_komentar = $_POST['isi_komentar'];

    $id_penulis = $_SESSION['id_penulis'];
    // Kita juga simpan nama penulis saat itu untuk data historis
    $nama_pengomentar = $_SESSION['nama_penulis']; 

    // Validasi sederhana agar tidak ada data kosong yang masuk
    if ($id_artikel > 0 && !empty($isi_komentar)) {

        // Siapkan query INSERT sesuai struktur tabel Anda
        $stmt = $conn->prepare("INSERT INTO komentar (id_artikel, id_penulis, nama_pengomentar, isi_komentar, tanggal_komentar) 
                                 VALUES (?, ?, ?, ?, NOW())");

        // Bind parameter
        $stmt->bind_param("isss", $id_artikel, $id_penulis, $nama_pengomentar, $isi_komentar);

        // Eksekusi query
        $stmt->execute();
    }

    // Setelah selesai, kembalikan user ke halaman artikel
    header("Location: article.php?id=" . $id_artikel);
    exit;

} else {
    // Jika diakses langsung tanpa POST, kembalikan ke halaman utama
    header('Location: index.php');
    exit;
}
?>