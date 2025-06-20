<?php
include 'db.php';  // Include the database connection
include 'footer.php'; 
include 'header.php';
include 'admin_only.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the search form is submitted
$searchQuery = '';
$kategoriQuery = '';  // Variable to hold the category filter

// Check if search is submitted
if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
}

// Check if a category is selected
if (isset($_GET['kategori'])) {
    $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);
    $kategoriQuery = "AND kategori.nama_kategori = '$kategori'";  // Filter by category
}

// Prepare the query to fetch articles based on search and category
$query = "SELECT * FROM artikel 
          INNER JOIN kategori ON artikel.id_kategori = kategori.id_kategori
          WHERE kategori.nama_kategori LIKE '%$searchQuery%' OR artikel.isi LIKE '%$searchQuery%' $kategoriQuery";

$result = mysqli_query($conn, $query);
?>

<main class="article-container">
    <section class="category-list">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                ?>
                <div class="category-card">
                    <a href="article.php?id=<?= $data['id_artikel'] ?>">
                        <img src="image/<?= htmlspecialchars($data['gambar']) ?>" alt="<?= htmlspecialchars($data['Judul']) ?>" />
                        <div class="category-label"><?= htmlspecialchars($data['Judul']) ?></div>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<p>No articles found for your search or category.</p>';
        }
        ?>
    </section>
</main>
