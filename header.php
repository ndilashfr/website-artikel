<?php
if (!isset($title)) $title = "Web Artikel";  // Default title jika belum didefinisikan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/dark-mode.css" id="dark-theme-style" disabled>
<script src="theme-switcher.js" defer></script>
</head>
<body>
<?php
  // Wajib! Untuk akses $_SESSION
?>

<!-- INFO LOGIN -->
<?php if (isset($_SESSION['nama_penulis'])): ?>
  <div class="text-end p-3" style="background-color: #eee;">
    👋 Halo, <strong><?= htmlspecialchars($_SESSION['nama_penulis']) ?></strong> 
    (<?= $_SESSION['role'] ?>) | 
    <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
  </div>
<?php endif; ?>

</body>
