<?php
session_start();
include 'db.php';
include 'admin_only.php';

$pesan = ''; // Variabel untuk menampilkan pesan notifikasi

// Validasi ID kategori dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php#tab-kategori");
    exit;
}
$id = intval($_GET['id']);

// PROSES FORM UPDATE DI BAGIAN PALING ATAS
if (isset($_POST['update'])) {
    $nama = $_POST['nama_kategori'];
    $keterangan = $_POST['keterangan'];

    $update_query = "UPDATE kategori SET nama_kategori = ?, keterangan = ? WHERE id_kategori = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("ssi", $nama, $keterangan, $id);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Kategori berhasil diperbarui!'];
        header("Location: admin_dashboard.php#tab-kategori");
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengupdate kategori.</div>";
    }
}

// AMBIL DATA KATEGORI UNTUK DITAMPILKAN DI FORM
$stmt_get = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$result = $stmt_get->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: admin_dashboard.php#tab-kategori");
    exit;
}

// Set judul dan panggil header yang BENAR
$title = "Edit Kategori: " . htmlspecialchars($data['nama_kategori']);
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="mb-0">Edit Kategori</h3>
            </div>
            <div class="card-body p-4">
                
                <?php echo $pesan; // Tampilkan pesan error jika ada ?>

                <form action="kategori_edit.php?id=<?= $id ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori:</label>
                        <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan:</label>
                        <textarea name="keterangan" class="form-control" rows="4"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="admin_dashboard.php#tab-kategori" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer yang BENAR
include 'layout_footer.php';
?>