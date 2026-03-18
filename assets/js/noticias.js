// assets/js/noticias.js — Paginación y filtros AJAX con animación suave

(function () {
    'use strict';

    // ── Elementos principales ──
    const zonaGrid = document.getElementById('noticias-zona');     // grid de tarjetas
    const zonaPag = document.getElementById('paginacion-zona');   // paginación
    const zonaContador = document.getElementById('noticias-count');    // "X resultados"
    const zonaTitulo = document.getElementById('noticias-titulo');   // "Últimas Noticias"
    const searchForm = document.getElementById('searchForm');
    const archivoSelect = document.getElementById('archivoSelect');
    const BASE_URL = window.SITE_BASE ?? '';

    if (!zonaGrid) return; // Solo activo en la página de noticias

    // ── Estado actual ──
    let estado = {
        pagina: 1,
        cat: '',
        q: '',
        mes: '',
    };

    // ── Inicializar estado desde URL actual ──
    function initEstado() {
        const p = new URLSearchParams(window.location.search);
        estado.pagina = parseInt(p.get('pagina') ?? '1');
        estado.cat = p.get('cat') ?? '';
        estado.q = p.get('q') ?? '';
        estado.mes = p.get('mes') ?? '';
    }

    // ── Construir query string ──
    function buildQuery(extra = {}) {
        const s = { ...estado, ...extra };
        const p = new URLSearchParams();
        if (s.pagina > 1) p.set('pagina', s.pagina);
        if (s.cat) p.set('cat', s.cat);
        if (s.q) p.set('q', s.q);
        if (s.mes) p.set('mes', s.mes);
        return p.toString() ? '?' + p.toString() : '';
    }

    // ── Animar salida del grid ──
    function fadeOut(el, cb) {
        el.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateY(8px)';
        setTimeout(cb, 260);
    }

    // ── Animar entrada del grid ──
    function fadeIn(el) {
        el.style.transition = 'none';
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                el.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
        });
    }

    // ── Skeleton loader mientras carga ──
    function mostrarSkeleton() {
        const skeletons = Array.from({ length: 4 }, () => `
      <div class="noticia-card-lista nc-skeleton">
        <div class="nc-img-wrap sk-img"></div>
        <div class="nc-body">
          <div class="sk-bar sk-cat"></div>
          <div class="sk-bar sk-title"></div>
          <div class="sk-bar sk-title sk-title-short"></div>
          <div class="sk-bar sk-text"></div>
          <div class="sk-bar sk-text sk-text-short"></div>
        </div>
      </div>
    `).join('');
        zonaGrid.innerHTML = skeletons;
    }

    // ── Scroll suave hasta el grid (sin ir al top) ──
    function scrollAlGrid() {
        const offset = zonaGrid.getBoundingClientRect().top + window.scrollY - 90;
        window.scrollTo({ top: Math.max(0, offset), behavior: 'smooth' });
    }

    // ── Petición AJAX ──
    async function cargarNoticias(nuevoEstado = {}) {
        // Mezclar nuevo estado
        estado = { ...estado, ...nuevoEstado };

        const qs = buildQuery();
        const url = BASE_URL + '/noticias' + qs;

        // Actualizar URL sin recargar
        window.history.pushState(estado, '', url);

        // Fade out suave del grid
        fadeOut(zonaGrid, async () => {
            mostrarSkeleton();
            fadeIn(zonaGrid);

            try {
                const res = await fetch(url + (qs ? '&' : '?') + 'ajax=1', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!res.ok) throw new Error('Error ' + res.status);

                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Extraer solo las zonas que necesitamos actualizar
                const nuevoGrid = doc.getElementById('noticias-zona');
                const nuevaPag = doc.getElementById('paginacion-zona');
                const nuevoCount = doc.getElementById('noticias-count');
                const nuevoTit = doc.getElementById('noticias-titulo');

                // Animar salida del skeleton y entrada del contenido real
                fadeOut(zonaGrid, () => {
                    if (nuevoGrid) zonaGrid.innerHTML = nuevoGrid.innerHTML;
                    if (nuevaPag) zonaPag.innerHTML = nuevaPag.innerHTML;
                    if (nuevoCount && zonaContador) zonaContador.textContent = nuevoCount.textContent;
                    if (nuevoTit && zonaTitulo) zonaTitulo.innerHTML = nuevoTit.innerHTML;

                    fadeIn(zonaGrid);
                    rebindPaginacion();
                    rebindCategorias();
                    scrollAlGrid();
                });

            } catch (err) {
                // Si falla AJAX, navegar normalmente
                window.location.href = url;
            }
        });
    }

    // ── Bind paginación (delegado para contenido dinámico) ──
    function rebindPaginacion() {
        if (!zonaPag) return;
        zonaPag.querySelectorAll('a.pag-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const urlBtn = new URL(btn.href);
                const pag = parseInt(urlBtn.searchParams.get('pagina') ?? '1');
                cargarNoticias({ pagina: pag });
            });
        });
    }

    // ── Bind links de categorías en sidebar ──
    function rebindCategorias() {
        document.querySelectorAll('.cat-link').forEach(link => {
            // Evitar duplicar listeners
            if (link.dataset.ajaxBound) return;
            link.dataset.ajaxBound = '1';

            link.addEventListener('click', e => {
                e.preventDefault();
                const urlLink = new URL(link.href);
                const cat = urlLink.searchParams.get('cat') ?? '';
                cargarNoticias({ cat, pagina: 1, q: '', mes: '' });

                // Actualizar estado visual activo
                document.querySelectorAll('.cat-link').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    }

    // ── Buscador ──
    function bindBuscador() {
        if (!searchForm) return;
        searchForm.addEventListener('submit', e => {
            e.preventDefault();
            const q = searchForm.querySelector('input[name="q"]')?.value.trim() ?? '';
            cargarNoticias({ q, pagina: 1, cat: '', mes: '' });
        });
    }

    // ── Archivo histórico ──
    function bindArchivo() {
        if (!archivoSelect) return;
        archivoSelect.addEventListener('change', () => {
            const mes = archivoSelect.value;
            cargarNoticias({ mes, pagina: 1, cat: '', q: '' });
        });
    }

    // ── Back/Forward del navegador ──
    window.addEventListener('popstate', e => {
        if (e.state) {
            estado = e.state;
            cargarNoticias();
        }
    });

    // ── Init ──
    initEstado();
    rebindPaginacion();
    rebindCategorias();
    bindBuscador();
    bindArchivo();

})();