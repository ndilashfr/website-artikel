<?php
session_start();
include 'db.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_penulis = $_SESSION['id_penulis'];
    $id_artikel = (int)$_POST['id_artikel'];
    $action = $_POST['action'];

    if ($action == 'bookmark' && $id_artikel > 0) {
        // Jika aksinya 'bookmark', INSERT ke database
        $stmt = $conn->prepare("INSERT INTO bookmarks (id_penulis, id_artikel) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_penulis, $id_artikel);
        $stmt->execute();
    } elseif ($action == 'unbookmark' && $id_artikel > 0) {
        // Jika aksinya 'unbookmark', DELETE dari database
        $stmt = $conn->prepare("DELETE FROM bookmarks WHERE id_penulis = ? AND id_artikel = ?");
        $stmt->bind_param("ii", $id_penulis, $id_artikel);
        $stmt->execute();
    }
}

// Kembalikan pengguna ke halaman artikel sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>