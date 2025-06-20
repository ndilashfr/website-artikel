<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_penulis'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

if (isset($_POST['btn_simpan'])) {

    // Ambil data dari form
    $judul      = $_POST['judul'];
    $kategori   = (int)$_POST['kategori'];
    $isi        = $_POST['isi'];
    
    // ==============================================
    // BUAT TANGGAL DALAM FORMAT DATABASE (YYYY-MM-DD HH:II:SS)
    // ==============================================
    date_default_timezone_set("Asia/Jakarta");
    $tanggal_db = date("Y-m-d H:i:s");

    // Proses upload gambar (tidak ada perubahan di sini)
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambarName = $_FILES['gambar']['name'];
        $gambarTmp  = $_FILES['gambar']['tmp_name'];
        $uploadDir  = "image/";
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newFileName = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($gambarTmp, $uploadDir . $newFileName)) {
                
              // SIMPAN KE DATABASE (TANPA KOLOM TANGGAL, KARENA created_at OTOMATIS)
$id_penulis_artikel = $_SESSION['id_penulis']; // Ambil ID penulis dari sesi
$query = "INSERT INTO artikel (Judul, isi, gambar, id_penulis, id_kategori) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);

// Bind parameter (tanpa tanggal, 's' pertama dihapus)
mysqli_stmt_bind_param($stmt, "sssii", $judul, $isi, $newFileName, $id_penulis_artikel, $kategori);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Artikel berhasil dipublikasikan!'];
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<script>alert('Gagal menyimpan artikel ke database.'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Gagal mengupload gambar.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Format gambar tidak valid.');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Gambar wajib diisi.');window.history.back();</script>";
    }
} else {
    header('Location: index.php');
    exit;
}
?>