<?php
session_start();
// Memanggil semua file yang dibutuhkan
include 'db.php';
include 'function.php';

// Set judul halaman
$title = "Tentang Kami";
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="text-center mb-4">Your Daily Dose of Hype</h1>
                <p class="lead text-center mb-5" style="font-size: 1.25rem;">
                    Stuck di rutinitas? Let's go! 🚀 Your Daily Need Articles adalah cheat sheet-mu buat upgrade hari-hari. Kita kurasiin tempat makan paling viral, spot foto yang auto-masuk FYP, dan semua hal seru lainnya. No more FOMO, just pure good vibes. Let's make every day an adventure!
                </p>

                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Mulai Jelajah</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer
include 'layout_footer.php';
?>