<?php
session_start();
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'function.php';

$id_penulis_login = $_SESSION['id_penulis'];

// Query dengan JOIN untuk mengambil detail artikel yang di-bookmark
$stmt = $conn->prepare("SELECT a.*, k.nama_kategori
                        FROM bookmarks b
                        JOIN artikel a ON b.id_artikel = a.id_artikel
                        LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                        WHERE b.id_penulis = ?
                        ORDER BY b.created_at DESC");
$stmt->bind_param("i", $id_penulis_login);
$stmt->execute();
$result = $stmt->get_result();

$bookmarked_articles = [];
while ($row = $result->fetch_assoc()) {
    $bookmarked_articles[] = $row;
}

$title = "Artikel Disimpan";
include 'layout_header.php';
?>

<div class="row">
    <main class="col-lg-8">
        <h2 class="mb-4 border-bottom pb-2"> Your Bookmark Articles</h2>
        <?php if (!empty($bookmarked_articles)): ?>
            <?php foreach ($bookmarked_articles as $artikel): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <a href="article.php?id=<?= $artikel['id_artikel'] ?>">
                                <img src="image/<?= htmlspecialchars($artikel['gambar']) ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($artikel['Judul']) ?>" style="height: 100%; object-fit: cover;">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="text-decoration-none"><?= htmlspecialchars($artikel['Judul']) ?></a></h5>
                                <p class="card-text"><small class="text-muted"><?= formatTanggalIndonesia($artikel['created_at']) ?> | <a href="kategori_artikel.php?id=<?= $artikel['id_kategori'] ?>"><?= htmlspecialchars($artikel['nama_kategori']) ?></a></small></p>
                                <p class="card-text"><?= substr(strip_tags($artikel['isi']), 0, 150) ?>...</p>
                                <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="btn btn-primary btn-sm">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Anda belum menyimpan artikel apapun.</div>
        <?php endif; ?>
    </main>

    <?php include 'layout_sidebar.php'; ?>
</div>

<?php
include 'layout_footer.php';
?>