<?php
session_start();
include 'db.php';

// Jika pengguna SUDAH LOGIN, arahkan ke halaman yang sesuai
if (isset($_SESSION['id_penulis'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin_dashboard.php' : 'index.php'));
    exit;
}

$login_error = ''; 

// Proses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];
    $pass = $_POST['password'];

    $query = "SELECT id_penulis, email, nama_penulis, role, password FROM penulis WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // Menggunakan pengecekan password teks biasa dengan trim()
        if ($user && trim($pass) === trim($user['password'])) { 
            $_SESSION['id_penulis'] = $user['id_penulis'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama_penulis'] = $user['nama_penulis'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . ($user['role'] == 'admin' ? 'admin_dashboard.php' : 'index.php'));
            exit;
        } else {
            $login_error = "Email atau password salah.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $login_error = "Terjadi kesalahan pada query database.";
    }
}
if (isset($conn)) { mysqli_close($conn); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hi! Let's Connect With Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/theme-purple.css">
</head>
<body class="login-page">
    <div class="login-container">
        <form action="login.php" method="POST">
            <h2 class="mb-4">Login</h2>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($login_error) ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <input type="email" class="form-control" name="Email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>
</html>