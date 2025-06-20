<?php
include_once 'db.php';
// admin_only.php tidak perlu di include di modal karena modal ini di load dari halaman admin_dashboard yg sudah di proteksi
// include_once 'admin_only.php';

// Proses penambahan penulis
if (isset($_POST['btn_tambah_penulis'])) {
    $nama_penulis = mysqli_real_escape_string($conn, $_POST['nama_penulis']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_plain = $_POST['password']; // Ambil password mentah
    $role = 'penulis'; // Default role untuk penulis baru

    // Validasi email
    $check_email = mysqli_query($conn, "SELECT email FROM penulis WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Email sudah terdaftar.'];
        header("Location: admin_dashboard.php#tab-penulis");
        exit;
    }

    // Hash password sebelum disimpan
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    $query = "INSERT INTO penulis (nama_penulis, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nama_penulis, $email, $password_hashed, $role);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Penulis berhasil ditambahkan!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan penulis: ' . mysqli_error($conn)];
    }
    header("Location: admin_dashboard.php#tab-penulis"); // Redirect ke tab penulis
    exit;
}
?>

<div class="modal fade" id="addPenulisModal" tabindex="-1" aria-labelledby="addPenulisModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPenulisModalLabel">Tambah Penulis Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="penulis_add_modal.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_penulis" class="form-label">Nama Penulis:</label>
                        <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <input type="hidden" name="role" value="penulis">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="btn_tambah_penulis" class="btn btn-primary">Simpan Penulis</button>
                </div>
            </form>
        </div>
    </div>
</div>