// assets/js/main.js — EMOVTT Santa Rosa

document.addEventListener('DOMContentLoaded', () => {

    const navbar = document.getElementById('siteNavbar');
    const hamburger = document.getElementById('navHamburger');
    const navMenu = document.getElementById('navMenu');

    // ---- Hamburger: abrir/cerrar menu ----
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = navMenu.classList.toggle('open');
            hamburger.classList.toggle('open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen);
        });
    }

    // ---- Dropdowns en mobile (click) ----
    document.querySelectorAll('.nav-link[data-toggle="dropdown"]').forEach(link => {
        link.addEventListener('click', (e) => {
            // Solo en mobile (menu colapsado)
            if (window.innerWidth > 992) return;
            e.preventDefault();
            const item = link.closest('.nav-item');
            const dropdown = item.querySelector('.nav-dropdown');
            if (!dropdown) return;

            // Cerrar otros
            document.querySelectorAll('.nav-item.open').forEach(el => {
                if (el !== item) {
                    el.classList.remove('open');
                    el.querySelector('.nav-dropdown')?.classList.remove('open');
                }
            });

            item.classList.toggle('open');
            dropdown.classList.toggle('open');
        });
    });

    // ---- Cerrar menu al click fuera ----
    document.addEventListener('click', (e) => {
        if (!navbar.contains(e.target)) {
            navMenu?.classList.remove('open');
            hamburger?.classList.remove('open');
            document.querySelectorAll('.nav-item.open').forEach(el => {
                el.classList.remove('open');
                el.querySelector('.nav-dropdown')?.classList.remove('open');
            });
        }
    });

    // ---- Navbar sombra en scroll ----
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });
    }

    // ---- Animacion de estadisticas ----
    const counters = document.querySelectorAll('.stat-num');
    const animateCounter = (el) => {
        const original = el.textContent;
        const num = parseInt(original.replace(/\D/g, ''));
        const suffix = original.replace(/[\d]/g, '');
        if (!num) return;
        let current = 0;
        const step = Math.ceil(num / 55);
        const timer = setInterval(() => {
            current += step;
            if (current >= num) { current = num; clearInterval(timer); }
            el.textContent = current.toLocaleString() + suffix;
        }, 22);
    };

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { animateCounter(e.target); obs.unobserve(e.target); }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => obs.observe(c));
    }

});