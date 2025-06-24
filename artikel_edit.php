<?php
session_start();
include 'db.php';
include 'function.php';
include 'admin_only.php';

$pesan = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}
$id_artikel_edit = intval($_GET['id']);

// ======================================================
// PROSES FORM UPDATE DI BAGIAN PALING ATAS
// ======================================================
if (isset($_POST['update'])) {
    // ... (Logika update data artikel utama tidak berubah) ...
    $tanggal = $_POST['tanggal'];
    $judul   = $_POST['judul'];
    $kategori= (int)$_POST['kategori'];
    $isi     = $_POST['isi'];
    $gambar_lama = $_POST['gambar_lama'];
    $gambar_untuk_db = $gambar_lama;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['name']) {
        // ... (Logika upload gambar baru tidak berubah) ...
        $gambarName = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));
        $newName = uniqid('img_', true) . '.' . $ext;
        if (move_uploaded_file($tmp, "image/" . $newName)) {
            $gambar_untuk_db = $newName;
            if (!empty($gambar_lama) && file_exists("image/" . $gambar_lama)) {
                unlink("image/" . $gambar_lama);
            }
        }
    }
    
    // Query update artikel utama
    $update_query = "UPDATE artikel SET tanggal=?, Judul=?, isi=?, gambar=?, id_kategori=? WHERE id_artikel=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssii", $tanggal, $judul, $isi, $gambar_untuk_db, $kategori, $id_artikel_edit);
    
    if ($stmt->execute()) {
        // ==============================================
        // LOGIKA BARU UNTUK UPDATE TAGS
        // ==============================================

        // 1. Hapus semua koneksi tag lama untuk artikel ini
        $stmt_delete_tags = $conn->prepare("DELETE FROM artikel_tag WHERE id_artikel = ?");
        $stmt_delete_tags->bind_param("i", $id_artikel_edit);
        $stmt_delete_tags->execute();

        // 2. Jalankan kembali logika proses tag yang sama seperti di proses_artikel.php
        if (isset($_POST['tags']) && !empty($_POST['tags'])) {
            $tags_array = explode(',', $_POST['tags']);
            foreach ($tags_array as $nama_tag) {
                $nama_tag_bersih = strtolower(trim($nama_tag));
                if (!empty($nama_tag_bersih)) {
                    $stmt_check_tag = $conn->prepare("SELECT id_tag FROM tags WHERE nama_tag = ?");
                    $stmt_check_tag->bind_param("s", $nama_tag_bersih);
                    $stmt_check_tag->execute();
                    $result_tag = $stmt_check_tag->get_result();
                    if ($result_tag->num_rows > 0) {
                        $tag_data = $result_tag->fetch_assoc();
                        $id_tag = $tag_data['id_tag'];
                    } else {
                        $stmt_insert_tag = $conn->prepare("INSERT INTO tags (nama_tag) VALUES (?)");
                        $stmt_insert_tag->bind_param("s", $nama_tag_bersih);
                        $stmt_insert_tag->execute();
                        $id_tag = $conn->insert_id;
                    }
                    $stmt_link_tag = $conn->prepare("INSERT INTO artikel_tag (id_artikel, id_tag) VALUES (?, ?)");
                    $stmt_link_tag->bind_param("ii", $id_artikel_edit, $id_tag);
                    $stmt_link_tag->execute();
                }
            }
        }
        // ==============================================

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Artikel berhasil diperbarui!'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengupdate artikel.</div>";
    }
}

// ======================================================
// AMBIL DATA ARTIKEL DAN TAGS-NYA UNTUK DITAMPILKAN DI FORM
// ======================================================
$stmt_get = $conn->prepare("SELECT * FROM artikel WHERE id_artikel = ?");
$stmt_get->bind_param("i", $id_artikel_edit);
$stmt_get->execute();
$result = $stmt_get->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: admin_dashboard.php");
    exit;
}

// Query baru untuk mengambil semua tag yang berhubungan dengan artikel ini
$stmt_get_tags = $conn->prepare("SELECT t.nama_tag FROM tags t JOIN artikel_tag at ON t.id_tag = at.id_tag WHERE at.id_artikel = ?");
$stmt_get_tags->bind_param("i", $id_artikel_edit);
$stmt_get_tags->execute();
$result_tags = $stmt_get_tags->get_result();
$tags_arr = [];
while ($row_tag = $result_tags->fetch_assoc()) {
    $tags_arr[] = $row_tag['nama_tag'];
}
// Gabungkan array tag menjadi string yang dipisahkan koma untuk ditampilkan di form
$tags_string = implode(', ', $tags_arr);

$title = "Edit Artikel: " . htmlspecialchars($data['Judul']);
include 'layout_header.php'; // Panggil header setelah semua data siap
?>

<div class="row">
    <div class="col-md-9 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3"><h3 class="mb-0">Edit Artikel</h3></div>
            <div class="card-body p-4">
                <?php echo $pesan; ?>
                <form action="artikel_edit.php?id=<?= $id_artikel_edit ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']) ?>">
                    <div class="mb-3"><label class="form-label fw-bold">Tanggal:</label><input type="text" name="tanggal" class="form-control" value="<?= htmlspecialchars($data['tanggal']) ?>" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Judul:</label><input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['Judul']) ?>" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Kategori:</label>
                        <select name="kategori" class="form-select">
                        <?php
                        $kategori_list = mysqli_query($conn, "SELECT * FROM kategori");
                        while ($k = mysqli_fetch_assoc($kategori_list)) {
                            $selected = ($k['id_kategori'] == $data['id_kategori']) ? "selected" : "";
                            echo "<option value='{$k['id_kategori']}' $selected>{$k['nama_kategori']}</option>";
                        }
                        ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label fw-bold">Tags (pisahkan dengan koma):</label>
                        <input type="text" class="form-control" id="tags" name="tags" value="<?= htmlspecialchars($tags_string) ?>" placeholder="Contoh: healing, kuliner, murah">
                    </div>

                    <div class="mb-4"><label class="form-label fw-bold">Isi Artikel:</label><textarea name="isi" id="isi" rows="10" class="form-control"><?= htmlspecialchars($data['isi']) ?></textarea></div>
                    <div class="mb-4"><label class="form-label fw-bold">Gambar Saat Ini:</label><br><img src="image/<?= htmlspecialchars($data['gambar']) ?>" width="200" class="img-thumbnail mb-2"><br><label class="form-label">Ganti Gambar:</label><input type="file" name="gambar" class="form-control"><small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small></div>

                    <div class="d-flex justify-content-end"><a href="admin_dashboard.php" class="btn btn-secondary me-2">Kembali</a><button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script> ClassicEditor.create( document.querySelector( '#isi' ) ).catch( error => { console.error( error ); } ); </script>

<?php 
include 'layout_footer.php'; 
?>