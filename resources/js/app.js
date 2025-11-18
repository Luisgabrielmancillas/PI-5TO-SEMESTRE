import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Año en footer (opcional)
document.getElementById('y') && (document.getElementById('y').textContent = new Date().getFullYear());

// Conector del theme-toggle (si usas botones con data-theme-toggle)
(function () {
function syncIcon(btn) {
    const isDark = document.documentElement.classList.contains('dark');
    btn.querySelector('[data-theme-sun]')?.classList.toggle('hidden', isDark);
    btn.querySelector('[data-theme-moon]')?.classList.toggle('hidden', !isDark);
}
function bindToggle(btn) {
    syncIcon(btn);
    btn.addEventListener('click', () => {
    const el = document.documentElement;
    const nowDark = el.classList.toggle('dark');
    localStorage.setItem('theme', nowDark ? 'dark' : 'light');
    document.querySelectorAll('[data-theme-toggle]').forEach(syncIcon);
    });
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-theme-toggle]').forEach(bindToggle);
});
})();

(function () {
const btn = document.getElementById('scrollTopBtn');
if (!btn) return;

btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
})();

(function () {
const links = document.querySelectorAll('a[data-scroll]');
links.forEach(link => {
    link.addEventListener('click', (e) => {
    const sel = link.getAttribute('data-scroll');
    const target = document.querySelector(sel);
    if (!target) return;

    e.preventDefault(); // evita que se cambie el hash
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Si había hash previo, lo limpiamos sin recargar
    if (location.hash) {
        history.replaceState(null, '', location.pathname + location.search);
    }
    });
});
})();

