<?php
session_start();
include 'db.php';

// Pastikan hanya pengguna yang sudah login yang bisa mengakses file ini
if (!isset($_SESSION['id_penulis'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

if (isset($_POST['btn_simpan'])) {

    // Ambil semua data dari form
    $judul      = $_POST['judul'];
    $kategori   = (int)$_POST['kategori'];
    $isi        = $_POST['isi'];
    $id_penulis_artikel = $_SESSION['id_penulis']; // Ambil ID penulis dari sesi
    
    // Siapkan tanggal dalam format DATETIME untuk database
    date_default_timezone_set("Asia/Jakarta");
    $created_at = date("Y-m-d H:i:s");

    // Proses upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambarName = $_FILES['gambar']['name'];
        $gambarTmp  = $_FILES['gambar']['tmp_name'];
        $uploadDir  = "image/";
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newFileName = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($gambarTmp, $uploadDir . $newFileName)) {
                
                // SIMPAN DATA ARTIKEL UTAMA TERLEBIH DAHULU
                $query = "INSERT INTO artikel (Judul, isi, gambar, id_penulis, id_kategori, created_at) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssiss", $judul, $isi, $newFileName, $id_penulis_artikel, $kategori, $created_at);

                if ($stmt->execute()) {
                    // Jika artikel berhasil disimpan, dapatkan ID artikel yang baru
                    $id_artikel_baru = $conn->insert_id;

                    // ==============================================
                    // LOGIKA BARU UNTUK MEMPROSES TAGS
                    // ==============================================
                    if (isset($_POST['tags']) && !empty($_POST['tags'])) {
                        // 1. Ubah string 'tag1, tag2' menjadi array ['tag1', 'tag2']
                        $tags_array = explode(',', $_POST['tags']);

                        foreach ($tags_array as $nama_tag) {
                            // 2. Bersihkan setiap tag dari spasi berlebih dan ubah ke huruf kecil
                            $nama_tag_bersih = strtolower(trim($nama_tag));

                            if (!empty($nama_tag_bersih)) {
                                // 3. Cek apakah tag sudah ada di tabel 'tags'
                                $stmt_check_tag = $conn->prepare("SELECT id_tag FROM tags WHERE nama_tag = ?");
                                $stmt_check_tag->bind_param("s", $nama_tag_bersih);
                                $stmt_check_tag->execute();
                                $result_tag = $stmt_check_tag->get_result();

                                if ($result_tag->num_rows > 0) {
                                    // Jika tag sudah ada, ambil ID-nya
                                    $tag_data = $result_tag->fetch_assoc();
                                    $id_tag = $tag_data['id_tag'];
                                } else {
                                    // Jika tag belum ada, masukkan ke tabel 'tags'
                                    $stmt_insert_tag = $conn->prepare("INSERT INTO tags (nama_tag) VALUES (?)");
                                    $stmt_insert_tag->bind_param("s", $nama_tag_bersih);
                                    $stmt_insert_tag->execute();
                                    // Lalu ambil ID dari tag yang baru saja dimasukkan
                                    $id_tag = $conn->insert_id;
                                }

                                // 4. Hubungkan artikel dengan tag di tabel 'artikel_tag'
                                $stmt_link_tag = $conn->prepare("INSERT INTO artikel_tag (id_artikel, id_tag) VALUES (?, ?)");
                                $stmt_link_tag->bind_param("ii", $id_artikel_baru, $id_tag);
                                $stmt_link_tag->execute();
                            }
                        }
                    }
                    // ==============================================
                    
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