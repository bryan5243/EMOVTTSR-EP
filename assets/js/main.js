document.addEventListener('DOMContentLoaded', () => {

    const hamburger = document.getElementById('navHamburger');
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('navOverlay');

    // MENU
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('open');
        hamburger.classList.toggle('open');
        overlay.classList.toggle('active');
    });

    // CERRAR
    overlay.addEventListener('click', () => {
        navMenu.classList.remove('open');
        hamburger.classList.remove('open');
        overlay.classList.remove('active');

        document.querySelectorAll('.nav-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('open'));
    });

    // DROPDOWN MOBILE
    document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
        const parent = link.parentElement;
        const dropdown = parent.querySelector('.nav-dropdown');

        if (!dropdown) return;

        link.addEventListener('click', (e) => {
            if (window.innerWidth > 992) return;

            e.preventDefault();
            const isOpen = dropdown.classList.contains('open');

            document.querySelectorAll('.nav-dropdown').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('open'));
            if (!isOpen) {
                dropdown.classList.add('open');
                parent.classList.add('open');
            }
        });
    });
});

