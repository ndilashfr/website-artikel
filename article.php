<?php
session_start();
// 1. PANGGIL SEMUA FILE YANG DIBUTUHKAN
include 'db.php';
include 'function.php';

// 2. AMBIL DATA ARTIKEL & ARTIKEL TERKAIT
$data = null;
$related_posts = [];
$title = "Artikel Tidak Ditemukan";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data artikel utama
    $stmt = $conn->prepare("SELECT a.*, k.nama_kategori 
                            FROM artikel a 
                            LEFT JOIN kategori k ON a.id_kategori = k.id_kategori 
                            WHERE a.id_artikel = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $title = htmlspecialchars($data['Judul']);

// ==> SISIPKAN KODE INI SETELAH BARIS "$title = htmlspecialchars($data['Judul']);"

// Logika untuk cek status bookmark
$is_bookmarked = false;
if (isset($_SESSION['id_penulis'])) {
    $id_penulis_login = $_SESSION['id_penulis'];
    $id_artikel_ini = $data['id_artikel'];
    $stmt_check = $conn->prepare("SELECT id_bookmark FROM bookmarks WHERE id_penulis = ? AND id_artikel = ?");
    $stmt_check->bind_param("ii", $id_penulis_login, $id_artikel_ini);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $is_bookmarked = true;
    }
}

// Logika untuk mengambil tags artikel ini
$article_tags = [];
$stmt_get_tags = $conn->prepare("SELECT t.nama_tag FROM tags t JOIN artikel_tag at ON t.id_tag = at.id_tag WHERE at.id_artikel = ?");
$stmt_get_tags->bind_param("i", $id);
$stmt_get_tags->execute();
$result_tags = $stmt_get_tags->get_result();
while ($row_tag = $result_tags->fetch_assoc()) {
    $article_tags[] = $row_tag;
}


        // Jika artikel ditemukan, ambil artikel terkait dari kategori yang sama
        $id_kategori_terkait = $data['id_kategori'];
        // DIUBAH: Mengurutkan berdasarkan created_at agar konsisten
        $stmt_terkait = $conn->prepare("SELECT id_artikel, Judul FROM artikel 
                                        WHERE id_kategori = ? AND id_artikel != ? 
                                        ORDER BY created_at DESC LIMIT 5");
        $stmt_terkait->bind_param("ii", $id_kategori_terkait, $id);
        $stmt_terkait->execute();
        $related_posts = $stmt_terkait->get_result();
    }
}

// 3. PANGGIL LAYOUT HEADER
include 'layout_header.php';
?>

<?php
// 4. TAMPILKAN KONTEN UTAMA
if ($data):
?>
    <div class="row">
        <main class="col-lg-8">
            <div class="article-container">
                <h1 class="article-title mb-2"><?= htmlspecialchars($data['Judul']) ?></h1>
                
                <div class="article-meta text-muted mb-4 d-flex justify-content-start align-items-center">
    <div>
        <span><?= formatTanggalIndonesia($data['created_at']) ?></span> | 
        <a href="kategori_artikel.php?id=<?= $data['id_kategori'] ?>"><?= htmlspecialchars($data['nama_kategori']) ?></a>
    </div>

    <?php if (isset($_SESSION['id_penulis'])): ?>
    <form action="proses_bookmark.php" method="POST" class="d-inline-block ms-4">
        <input type="hidden" name="id_artikel" value="<?= $data['id_artikel'] ?>">
        <?php if ($is_bookmarked): ?>
            <input type="hidden" name="action" value="unbookmark">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-bookmark-check-fill"></i> Tersimpan
            </button>
        <?php else: ?>
            <input type="hidden" name="action" value="bookmark">
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-bookmark-plus"></i> Simpan
            </button>
        <?php endif; ?>
    </form>
    <?php endif; ?>
</div>

                <?php if (!empty($data['gambar'])): ?>
                    <img src="image/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($data['Judul']) ?>">
                <?php endif; ?>

                <div class="article-content">
                    <?= $data['isi'] ?>
                </div>



<?php if (!empty($article_tags)): ?>
    <div class="mt-4 pt-3 border-top">
        <strong>Tags:</strong>
        <?php foreach ($article_tags as $tag): ?>
            <a href="tag.php?tag=<?= urlencode($tag['nama_tag']) ?>" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                #<?= htmlspecialchars($tag['nama_tag']) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

                <hr class="my-5">

                <div class="comments-section">
                    <?php include 'komentar.php'; ?>
                </div>

                <div class="mt-5">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </main>

        <aside class="col-lg-4">
            <?php include 'widget_pencarian.php'; ?>

            <?php if ($related_posts && $related_posts->num_rows > 0): ?>
            <div class="card mb-4">
                <h5 class="card-header">Terkait di: <?= htmlspecialchars($data['nama_kategori']) ?></h5>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php while ($post = $related_posts->fetch_assoc()): ?>
                            <li>
                                <a href="article.php?id=<?= $post['id_artikel'] ?>"><?= htmlspecialchars($post['Judul']) ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>

<?php else:
    echo "<div class='alert alert-danger text-center'>Artikel yang Anda cari tidak ditemukan.</div>";
endif;
?>

<?php
// 5. PANGGIL FOOTER
include 'layout_footer.php';
?>