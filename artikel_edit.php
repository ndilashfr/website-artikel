<?php
// SELALU MULAI DENGAN SESSION START DAN INCLUDE
session_start();
include 'db.php';
include 'function.php';
include 'admin_only.php';

$pesan = ''; // Variabel untuk menampilkan pesan notifikasi

// ======================================================
// LANGKAH 1: PROSES FORM UPDATE DI BAGIAN PALING ATAS
// ======================================================
if (isset($_POST['update'])) {
    // Ambil semua data dari form
    $id_to_update = (int)$_GET['id'];
    $tanggal      = $_POST['tanggal'];
    $judul        = $_POST['judul'];
    $kategori     = (int)$_POST['kategori']; // <-- Variabel yang tertukar sudah diperbaiki
    $isi          = $_POST['isi'];
    
    // Ambil data gambar lama dari database untuk jaga-jaga
    $stmt_old_img = $conn->prepare("SELECT gambar FROM artikel WHERE id_artikel = ?");
    $stmt_old_img->bind_param("i", $id_to_update);
    $stmt_old_img->execute();
    $result_old_img = $stmt_old_img->get_result();
    $data_old = $result_old_img->fetch_assoc();
    $gambar_lama = $data_old['gambar'];

    // Cek apakah ada gambar baru yang di-upload
    if ($_FILES['gambar']['name']) {
        $gambarName = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));
        $newName = uniqid('img_', true) . '.' . $ext;
        move_uploaded_file($tmp, "image/" . $newName);
        
        // Hapus gambar lama jika ada
        if (!empty($gambar_lama) && file_exists("image/" . $gambar_lama)) {
            unlink("image/" . $gambar_lama);
        }
        $gambar_untuk_db = $newName;
    } else {
        // Jika tidak ada gambar baru, gunakan nama gambar yang lama
        $gambar_untuk_db = $gambar_lama;
    }

    // Update ke database menggunakan prepared statement
    $update_query = "UPDATE artikel SET tanggal=?, Judul=?, isi=?, gambar=?, id_kategori=? WHERE id_artikel=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssii", $tanggal, $judul, $isi, $gambar_untuk_db, $kategori, $id_to_update);
    
    if (mysqli_stmt_execute($stmt)) {
        // Set pesan sukses ke session dan redirect
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Artikel berhasil diperbarui!'];
        header("Location: admin_dashboard.php"); // Arahkan ke dashboard
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengupdate artikel.</div>";
    }
}


// ======================================================
// LANGKAH 2: AMBIL DATA UNTUK DITAMPILKAN DI FORM
// ======================================================
if (!isset($_GET['id'])) {
    // Redirect jika tidak ada ID
    header("Location: admin_dashboard.php");
    exit;
}
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM artikel WHERE id_artikel = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    // Redirect jika artikel tidak ditemukan
    header("Location: admin_dashboard.php");
    exit;
}

// Set judul dan panggil header
$title = "Edit Artikel: " . htmlspecialchars($data['Judul']);
include 'header.php'; // Di sini kita asumsikan header.php tidak mencetak HTML body
?>

<div class="container mt-5">
    <h2>Edit Artikel</h2>
    
    <?php echo $pesan; // Tampilkan pesan error jika ada ?>

    <form action="artikel_edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tanggal:</label>
            <input type="text" name="tanggal" class="form-control" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul:</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['Judul']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori:</label>
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
            <label class="form-label">Isi Artikel:</label>
            <textarea name="isi" id="isi" rows="10" class="form-control"><?= htmlspecialchars($data['isi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini:</label><br>
            <img src="image/<?= htmlspecialchars($data['gambar']) ?>" width="200" class="img-thumbnail mb-2"><br>
            <label class="form-label">Ganti Gambar:</label>
            <input type="file" name="gambar" class="form-control">
            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>

        <button type="submit" class="btn btn-primary" name="update">Simpan Perubahan</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#isi' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

<?php 
// Panggil footer di sini jika ada
include 'footer.php'; 
?>