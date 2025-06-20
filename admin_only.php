<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_penulis'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak. Halaman ini hanya untuk Administrator.');
            window.location='index.php';
          </script>";
    exit;
}
