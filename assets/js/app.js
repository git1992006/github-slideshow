const root = document.documentElement;
const savedTheme = localStorage.getItem('dsb-theme');
if (savedTheme) {
  root.setAttribute('data-bs-theme', savedTheme);
}

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('themeToggle');
  if (toggle) {
    toggle.addEventListener('click', () => {
      const current = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-bs-theme', current);
      localStorage.setItem('dsb-theme', current);
    });
  }
});
