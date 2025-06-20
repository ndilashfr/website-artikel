<?php
session_start();
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'function.php';

// ======================================================
// LOGIKA PENGAMBILAN DATA
// ======================================================
$searchQuery = $_GET['search'] ?? '';
$articles = [];
$page_title = 'Artikel Terbaru';

if (!empty($searchQuery)) {
    // Jika ada pencarian
    $page_title = 'Hasil Pencarian untuk: "' . htmlspecialchars($searchQuery) . '"';
    $searchParam = "%" . $searchQuery . "%";
    // DIUBAH: Mengurutkan berdasarkan created_at
    $stmt = $conn->prepare("SELECT a.*, k.nama_kategori FROM artikel a JOIN kategori k ON a.id_kategori = k.id_kategori WHERE a.Judul LIKE ? OR a.isi LIKE ? ORDER BY a.created_at DESC");
    $stmt->bind_param("ss", $searchParam, $searchParam);
} else {
    // Tampilan normal
    // DIUBAH: Mengurutkan berdasarkan created_at
    $stmt = $conn->prepare("SELECT a.*, k.nama_kategori FROM artikel a JOIN kategori k ON a.id_kategori = k.id_kategori ORDER BY a.created_at DESC LIMIT 7");
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

// Set judul halaman dan panggil header
$title = "Homepage";
include 'layout_header.php';
?>

<div class="row">
    
    <main class="col-lg-8">
        <h2 class="mb-4 border-bottom pb-2"><?= $page_title ?></h2>

        <?php if (!empty($articles)): ?>
            
            <?php 
            $featured_article = (empty($searchQuery)) ? array_shift($articles) : null;
            ?>

            <?php if ($featured_article): ?>
            <div class="card mb-4 shadow-sm">
                <?php if(!empty($featured_article['gambar'])): ?>
                    <a href="article.php?id=<?= $featured_article['id_artikel'] ?>">
                        <img src="image/<?= htmlspecialchars($featured_article['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($featured_article['Judul']) ?>" style="aspect-ratio: 16/9; object-fit: cover;">
                    </a>
                <?php endif; ?>
                <div class="card-body p-4">
                    <h3 class="card-title">
                        <a href="article.php?id=<?= $featured_article['id_artikel'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($featured_article['Judul']) ?>
                        </a>
                    </h3>
                    <p class="card-text">
                        <small class="text-muted">
                            <?= formatTanggalIndonesia($featured_article['created_at']) ?> | 
                            <a href="kategori_artikel.php?id=<?= $featured_article['id_kategori'] ?>"><?= htmlspecialchars($featured_article['nama_kategori']) ?></a>
                        </small>
                    </p>
                    <p class="card-text fs-5">
                        <?= substr(strip_tags($featured_article['isi']), 0, 200) ?>...
                    </p>
                    <a href="article.php?id=<?= $featured_article['id_artikel'] ?>" class="btn btn-primary mt-2">Baca Selengkapnya →</a>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($articles)): ?>
            <div class="row">
            <?php foreach ($articles as $artikel): ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100 d-flex flex-column">
            
            <a href="article.php?id=<?= $artikel['id_artikel'] ?>">
                <img src="image/<?= htmlspecialchars($artikel['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($artikel['Judul']) ?>" style="aspect-ratio: 16/9; object-fit: cover;">
            </a>

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">
                    <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="text-decoration-none">
                        <?= htmlspecialchars($artikel['Judul']) ?>
                    </a>
                </h5>
                <p class="card-text">
                    <small class="text-muted">
                        <?= formatTanggalIndonesia($artikel['created_at']) ?> | 
                        <a href="kategori_artikel.php?id=<?= $artikel['id_kategori'] ?>"><?= htmlspecialchars($artikel['nama_kategori']) ?></a>
                    </small>
                </p>
            </div>

            <div class="card-footer bg-transparent border-0 pb-3">
                 <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="btn btn-primary btn-sm">Baca Selengkapnya →</a>
            </div>

        </div>
    </div>
<?php endforeach; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning">Tidak ada artikel yang ditemukan.</div>
        <?php endif; ?>
    </main>

    <?php include 'layout_sidebar.php'; ?>

</div>

<?php
include 'layout_footer.php';
?>


<style>
.transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
}
</style>