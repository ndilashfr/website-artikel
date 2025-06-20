<?php
// SELALU MULAI DENGAN SESSION START DAN INCLUDE
session_start();
include 'db.php';
include 'admin_only.php';

$pesan = ''; // Variabel untuk menampilkan pesan notifikasi

// Validasi ID penulis dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php#tab-penulis");
    exit;
}
$id = intval($_GET['id']);


// ======================================================
// LANGKAH 1: PROSES FORM UPDATE DI BAGIAN PALING ATAS
// ======================================================
if (isset($_POST['update'])) {
    $nama = $_POST['nama_penulis'];
    $email = $_POST['email'];

    // Cek apakah admin memasukkan password baru
    if (!empty($_POST['password'])) {
        // HASH PASSWORD BARU! Ini penting untuk keamanan.
        $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $update_query = "UPDATE penulis SET nama_penulis = ?, email = ?, password = ? WHERE id_penulis = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $nama, $email, $password_hashed, $id);
    } else {
        // Jika password tidak diubah, jangan update kolom password
        $update_query = "UPDATE penulis SET nama_penulis = ?, email = ? WHERE id_penulis = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $nama, $email, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penulis berhasil diperbarui!'];
        header("Location: admin_dashboard.php#tab-penulis");
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengupdate data penulis.</div>";
    }
}


// ======================================================
// LANGKAH 2: AMBIL DATA PENULIS UNTUK DITAMPILKAN DI FORM
// ======================================================
$stmt_get = $conn->prepare("SELECT * FROM penulis WHERE id_penulis = ?");
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$result = $stmt_get->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: admin_dashboard.php#tab-penulis");
    exit;
}

// Set judul dan panggil header yang BENAR
$title = "Edit Penulis: " . htmlspecialchars($data['nama_penulis']);
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="mb-0">Edit Penulis</h3>
            </div>
            <div class="card-body p-4">
                
                <?php echo $pesan; // Tampilkan pesan error jika ada ?>

                <form action="penulis_edit.php?id=<?= $id ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Penulis:</label>
                        <input type="text" name="nama_penulis" class="form-control" value="<?= htmlspecialchars($data['nama_penulis']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru:</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                        <small class="form-text text-muted">Mengisi kolom ini akan mengganti password lama.</small>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="admin_dashboard.php#tab-penulis" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer yang BENAR
// Blok proses update ganda di bawah sudah dihapus
include 'layout_footer.php';
?>