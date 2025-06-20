<?php
// Jika sesi belum dimulai di file utama, mulai di sini
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Set judul default jika tidak didefinisikan di halaman lain
$title = $title ?? 'Your Daily Need Articles';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="css/theme-purple.css">
</head>
<body>
    <?php 
    // Memanggil file navigasi terpisah
    include 'nav.php'; 
    ?>
    
    <div class="container mt-5 flex-grow-1">