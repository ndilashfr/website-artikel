<?php
session_start();
// Pastikan hanya user yang sudah login bisa mengakses halaman ini
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'function.php';

// Ambil ID penulis dari sesi
$id_penulis_login = $_SESSION['id_penulis'];
$pesan = ''; // Variabel untuk pesan sukses/error

// PROSES FORM JIKA ADA SUBMIT (METHOD POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_baru = $_POST['nama_penulis'];
    $email_baru = $_POST['email'];

    // Cek apakah password baru diisi atau tidak
    if (!empty($_POST['password'])) {
        // Jika diisi, hash password baru
        $password_baru_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // Query untuk update nama, email, DAN password
        $update_stmt = $conn->prepare("UPDATE penulis SET nama_penulis = ?, email = ?, password = ? WHERE id_penulis = ?");
        $update_stmt->bind_param("sssi", $nama_baru, $email_baru, $password_baru_hashed, $id_penulis_login);
    } else {
        // Jika password kosong, query hanya update nama dan email
        $update_stmt = $conn->prepare("UPDATE penulis SET nama_penulis = ?, email = ? WHERE id_penulis = ?");
        $update_stmt->bind_param("ssi", $nama_baru, $email_baru, $id_penulis_login);
    }

    // Eksekusi query
    if ($update_stmt->execute()) {
        $pesan = "<div class='alert alert-success'>Profil berhasil diperbarui.</div>";
        // Perbarui juga nama di session agar langsung berubah di navigasi
        $_SESSION['nama_penulis'] = $nama_baru;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal memperbarui profil.</div>";
    }
}

// AMBIL DATA TERBARU PENGGUNA UNTUK DITAMPILKAN DI FORM
$stmt = $conn->prepare("SELECT * FROM penulis WHERE id_penulis = ?");
$stmt->bind_param("i", $id_penulis_login);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set judul halaman
$title = "Profil Saya";
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Edit Profil Saya</h3>
            </div>
            <div class="card-body">
                
                <?php echo $pesan; // Tampilkan pesan sukses/error di sini ?>

                <form action="profil.php" method="POST">
                    <div class="mb-3">
                        <label for="nama_penulis" class="form-label">Nama Lengkap:</label>
                        <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" value="<?= htmlspecialchars($user['nama_penulis']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <hr>
                  

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-5">
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php
include 'layout_footer.php';
?>