// admin_dashboard.js
document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');
    const tabs = document.querySelectorAll('.tab-content');
  
    navLinks.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        tabs.forEach(tab => tab.classList.add('d-none'));
        document.querySelector(this.getAttribute('data-bs-target')).classList.remove('d-none');
  
        navLinks.forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
      });
    });
  });
  document.getElementById('searchArtikel').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('#tab-artikel tbody tr').forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(keyword) ? '' : 'none';
    });
  });
  document.addEventListener('DOMContentLoaded', function () {
    // Tab logic
    const navLinks = document.querySelectorAll('.nav-link');
    const tabs = document.querySelectorAll('.tab-content');
  
    navLinks.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        tabs.forEach(tab => tab.classList.add('d-none'));
        document.querySelector(this.getAttribute('data-bs-target')).classList.remove('d-none');
  
        navLinks.forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
      });
    });
  
    // Filtering logic
    function setupFilter(inputId, tableId) {
      const input = document.getElementById(inputId);
      if (!input) return;
  
      input.addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll(`#${tableId} tbody tr`).forEach(row => {
          const text = row.innerText.toLowerCase();
          row.style.display = text.includes(keyword) ? '' : 'none';
        });
      });
    }
  
    setupFilter('searchArtikel', 'tabelArtikel');
    setupFilter('searchKategori', 'tabelKategori');
    setupFilter('searchPenulis', 'tabelPenulis');
  });
  