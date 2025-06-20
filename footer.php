<footer class="site-footer bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        <p>&copy; <?= date("Y"); ?> Your Daily Need Articles. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/admin_dashboard.js"></script>
<script src="js/preview_artikel.js"></script> <script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash) {
        const targetTab = document.querySelector(`a[data-bs-target="${window.location.hash}"]`);
        if (targetTab) {
            targetTab.click(); // Simulate click to activate tab
        }
    }
});
</script>
</body>
</html>