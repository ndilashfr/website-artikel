<?php
include_once 'db.php';
// admin_only.php tidak perlu di include di modal karena modal ini di load dari halaman admin_dashboard yg sudah di proteksi

// Proses penambahan kategori
if (isset($_POST['btn_tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Validasi nama kategori (opsional, untuk menghindari duplikat)
    $check_kategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE nama_kategori = '$nama_kategori'");
    if (mysqli_num_rows($check_kategori) > 0) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Nama kategori sudah ada.'];
        header("Location: admin_dashboard.php#tab-kategori");
        exit;
    }

    $query = "INSERT INTO kategori (nama_kategori, keterangan) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $nama_kategori, $keterangan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Kategori berhasil ditambahkan!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan kategori: ' . mysqli_error($conn)];
    }
    header("Location: admin_dashboard.php#tab-kategori"); // Redirect ke tab kategori
    exit;
}
?>

<div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="kategori_add_modal.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori:</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="btn_tambah_kategori" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>