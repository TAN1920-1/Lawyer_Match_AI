document.addEventListener('DOMContentLoaded', () => {
  const root = document.documentElement;
  const toggleBtn = document.getElementById('themeToggle');
  const stored = localStorage.getItem('theme') || 'dark';
  setTheme(stored);
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const current = root.getAttribute('data-theme') || 'light';
      const next = current === 'dark' ? 'light' : 'dark';
      setTheme(next);
    });
  }
  function setTheme(theme) {
    root.setAttribute('data-theme', theme);
    if (document.body) {
      document.body.setAttribute('data-theme', theme);
      document.body.classList.toggle('theme-dark', theme === 'dark');
    }
    root.classList.toggle('theme-dark', theme === 'dark');
    localStorage.setItem('theme', theme);
    if (toggleBtn) {
      toggleBtn.innerHTML = theme === 'dark'
        ? '<i class="fas fa-sun"></i>'
        : '<i class="fas fa-moon"></i>';
    }
  }

  // Sidebar toggle
  const menuToggle = document.getElementById('menu-toggle');
  if (menuToggle) {
    menuToggle.addEventListener('click', (e) => {
      e.preventDefault();
      const wrapper = document.getElementById('wrapper');
      if (wrapper) {
        wrapper.classList.toggle('toggled');
      }
    });
  }

  document.addEventListener('click', function(e){
    const link = e.target.closest('.dropdown-menu .dropdown-item');
    if (link) {
      const href = link.getAttribute('href');
      if (href && href !== '#') {
        e.preventDefault();
        window.location.assign(href);
      }
    }
  }, true);
});
