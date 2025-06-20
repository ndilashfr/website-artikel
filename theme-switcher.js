document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const darkTheme = document.getElementById('dark-theme-style');
    const themeIcon = themeToggle.querySelector('i');

    // Cek tema tersimpan dari kunjungan sebelumnya
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        darkTheme.disabled = false;
        themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
    }

    themeToggle.addEventListener('click', () => {
        if (darkTheme.disabled) {
            // Aktifkan dark mode
            darkTheme.disabled = false;
            localStorage.setItem('theme', 'dark');
            themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        } else {
            // Nonaktifkan dark mode
            darkTheme.disabled = true;
            localStorage.setItem('theme', 'light');
            themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
        }
    });
});