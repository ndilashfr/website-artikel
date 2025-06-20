<?php
session_start();
// Memanggil semua file yang dibutuhkan
include 'db.php';
include 'function.php';

// Set judul halaman
$title = "Hubungi Kami";
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="text-center mb-3">Hubungi Kami</h1>
                <p class="text-center text-muted mb-5">Punya pertanyaan, ide, atau ingin kolaborasi? Jangan ragu untuk menghubungi kami melalui channel di bawah ini!</p>
                
                <div class="list-group text-center">
                    <a href="mailto:kontak@dailyneedarticles.com" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-envelope-fill fs-4 me-3"></i>
                        <span class="fs-5">dailyneedarticles@gmail.com</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-instagram fs-4 me-3"></i>
                        <span class="fs-5">@DailyNeedArticles</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-twitter-x fs-4 me-3"></i>
                        <span class="fs-5">@DailyNeedID</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-tiktok fs-4 me-3"></i>
                        <span class="fs-5">@dailyneedarticles</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer
include 'layout_footer.php';
?>