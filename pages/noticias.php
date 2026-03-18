<?php
// pages/noticias.php
$page_title  = 'Noticias';
$active_page = 'noticias';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Noticia.php';
require_once __DIR__ . '/../controllers/NoticiaController.php';

// ── Usar el controller para obtener todos los datos ──
$ctrl = new NoticiaController();
$data = $ctrl->lista();

// Variables de la vista — tipadas explícitamente para que el IDE las reconozca
/** @var array  $noticias     Lista de noticias paginadas */
$noticias    = $data['noticias'];
/** @var int    $total        Total de resultados */
$total       = $data['total'];
/** @var int    $pagina       Página actual */
$pagina      = $data['pagina'];
/** @var int    $totalPaginas Total de páginas */
$totalPaginas = $data['totalPaginas'];
/** @var string $cat          Categoría activa ('' = todas) */
$cat         = $data['cat'];
/** @var string $busqueda     Término de búsqueda */
$busqueda    = $data['busqueda'];
/** @var string $mesFiltro    Mes del archivo histórico seleccionado */
$mesFiltro   = $data['mesFiltro'];
/** @var array|false $destacada  Noticia destacada o false */
$destacada   = $data['destacada'];
/** @var array  $masLeidas    Noticias más leídas */
$masLeidas   = $data['masLeidas'];
/** @var array  $categorias   Categorías con conteo */
$categorias  = $data['categorias'];
/** @var array  $archivo      Meses del archivo histórico */
$archivo     = $data['archivo'];

// ── Helpers de categoría (delegados al controller) ──
function catLabel(string $cat): string
{
    return NoticiaController::catLabel($cat);
}
function catColor(string $cat): string
{
    return NoticiaController::catColor($cat);
}

// ── Helper: URL con parámetros conservados ──
function buildUrl(array $params): string
{
    global $base;
    $base_params = [];
    if (!empty($_GET['cat']))  $base_params['cat']  = $_GET['cat'];
    if (!empty($_GET['q']))    $base_params['q']    = $_GET['q'];
    if (!empty($_GET['mes']))  $base_params['mes']  = $_GET['mes'];
    $merged = array_merge($base_params, $params);
    return $base . '/noticias?' . http_build_query($merged);
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ──────────────────────────────────────────────
     BREADCRUMB
────────────────────────────────────────────── -->
<div class="noticias-breadcrumb">
    <div class="container">
        <a href="<?= $base ?>/"><i class="fas fa-home"></i> Inicio</a>
        <i class="fas fa-chevron-right"></i>
        <span>Noticias</span>
        <?php if ($cat): ?>
            <i class="fas fa-chevron-right"></i>
            <span><?= catLabel($cat) ?></span>
        <?php elseif ($busqueda): ?>
            <i class="fas fa-chevron-right"></i>
            <span>Búsqueda: "<?= htmlspecialchars($busqueda) ?>"</span>
        <?php endif; ?>
    </div>
</div>

<!-- ──────────────────────────────────────────────
     LAYOUT PRINCIPAL: Contenido + Sidebar
────────────────────────────────────────────── -->
<div class="noticias-layout">
    <div class="container">
        <div class="noticias-wrapper">

            <!-- ══════════════════════════════
           COLUMNA PRINCIPAL
      ═══════════════════════════════ -->
            <main class="noticias-main">

                <!-- NOTICIA DESTACADA -->
                <?php if ($destacada && $pagina === 1 && $cat === '' && $busqueda === ''): ?>
                    <div class="noticia-hero">
                        <div class="noticia-hero-img">
                            <?php if ($destacada['imagen']): ?>
                                <img src="<?= $base ?>/uploads/noticias/<?= htmlspecialchars($destacada['imagen']) ?>"
                                    alt="<?= htmlspecialchars($destacada['titulo']) ?>">
                            <?php else: ?>
                                <div class="noticia-hero-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <div class="noticia-hero-overlay"></div>
                        </div>
                        <div class="noticia-hero-body">
                            <span class="noticia-badge-cat" style="background:<?= catColor($destacada['categoria']) ?>">
                                DESTACADO
                            </span>
                            <h1 class="noticia-hero-titulo">
                                <a href="<?= $base ?>/noticias/<?= $destacada['slug'] ?>">
                                    <?= htmlspecialchars($destacada['titulo']) ?>
                                </a>
                            </h1>
                            <p class="noticia-hero-resumen"><?= htmlspecialchars($destacada['resumen']) ?></p>
                            <div class="noticia-hero-meta">
                                <span><i class="far fa-calendar-alt"></i>
                                    <?= date('d M, Y', strtotime($destacada['fecha_publicacion'])) ?>
                                </span>
                                <span><i class="far fa-user"></i> <?= htmlspecialchars($destacada['autor']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- CABECERA SECCIÓN LISTA -->
                <div class="noticias-list-header">
                    <h2 class="noticias-list-title" id="noticias-titulo">
                        <span class="title-accent"></span>
                        <?php if ($busqueda): ?>
                            Resultados para "<?= htmlspecialchars($busqueda) ?>"
                        <?php elseif ($cat): ?>
                            <?= catLabel($cat) ?>
                        <?php else: ?>
                            Últimas Noticias
                        <?php endif; ?>
                    </h2>
                    <?php if ($total > 0): ?>
                        <span class="noticias-count" id="noticias-count"><?= $total ?> resultado<?= $total !== 1 ? 's' : '' ?></span>
                    <?php endif; ?>
                </div>

                <!-- GRID DE NOTICIAS -->
                <?php if (empty($noticias)): ?>
                    <div id="noticias-zona" class="noticias-empty">
                        <i class="fas fa-search"></i>
                        <p>No se encontraron noticias<?= $busqueda ? ' para "' . htmlspecialchars($busqueda) . '"' : '' ?>.</p>
                        <a href="<?= $base ?>/noticias" class="btn-azul" style="margin-top:12px">Ver todas las noticias</a>
                    </div>
                <?php else: ?>
                    <div id="noticias-zona" class="noticias-grid-lista">
                        <?php foreach ($noticias as $n):
                            $fecha = new DateTime($n['fecha_publicacion']);
                        ?>
                            <article class="noticia-card-lista">

                                <!-- Fecha esquina -->
                                <div class="nc-fecha">
                                    <span class="nc-dia"><?= $fecha->format('d') ?></span>
                                    <span class="nc-mes"><?= strtoupper($fecha->format('M')) ?></span>
                                </div>

                                <!-- Imagen -->
                                <a href="<?= $base ?>/noticias/<?= $n['slug'] ?>" class="nc-img-wrap">
                                    <?php if ($n['imagen']): ?>
                                        <img src="<?= $base ?>/uploads/noticias/<?= htmlspecialchars($n['imagen']) ?>"
                                            alt="<?= htmlspecialchars($n['titulo']) ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="nc-img-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <!-- Cuerpo -->
                                <div class="nc-body">
                                    <span class="nc-cat-badge" style="background:<?= catColor($n['categoria']) ?>">
                                        <?= catLabel($n['categoria']) ?>
                                    </span>
                                    <h3 class="nc-titulo">
                                        <a href="<?= $base ?>/noticias/<?= $n['slug'] ?>">
                                            <?= htmlspecialchars($n['titulo']) ?>
                                        </a>
                                    </h3>
                                    <p class="nc-resumen"><?= htmlspecialchars($n['resumen']) ?></p>
                                    <a href="<?= $base ?>/noticias/<?= $n['slug'] ?>" class="nc-leer-mas">
                                        Leer más <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>

                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- PAGINACIÓN -->
                    <?php if ($totalPaginas > 1): ?>
                        <nav id="paginacion-zona" class="paginacion" aria-label="Páginas de noticias">
                            <!-- Anterior -->
                            <?php if ($pagina > 1): ?>
                                <a href="<?= buildUrl(['pagina' => $pagina - 1]) ?>" class="pag-btn pag-arrow" aria-label="Anterior">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="pag-btn pag-arrow disabled"><i class="fas fa-chevron-left"></i></span>
                            <?php endif; ?>

                            <!-- Números -->
                            <?php
                            $rango    = 2;
                            $inicio   = max(1, $pagina - $rango);
                            $fin      = min($totalPaginas, $pagina + $rango);
                            if ($inicio > 1): ?>
                                <a href="<?= buildUrl(['pagina' => 1]) ?>" class="pag-btn">1</a>
                                <?php if ($inicio > 2): ?><span class="pag-dots">…</span><?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                                <?php if ($i === $pagina): ?>
                                    <span class="pag-btn pag-active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="<?= buildUrl(['pagina' => $i]) ?>" class="pag-btn"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($fin < $totalPaginas): ?>
                                <?php if ($fin < $totalPaginas - 1): ?><span class="pag-dots">…</span><?php endif; ?>
                                <a href="<?= buildUrl(['pagina' => $totalPaginas]) ?>" class="pag-btn"><?= $totalPaginas ?></a>
                            <?php endif; ?>

                            <!-- Siguiente -->
                            <?php if ($pagina < $totalPaginas): ?>
                                <a href="<?= buildUrl(['pagina' => $pagina + 1]) ?>" class="pag-btn pag-arrow" aria-label="Siguiente">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="pag-btn pag-arrow disabled"><i class="fas fa-chevron-right"></i></span>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>

            </main>
            <!-- /columna principal -->

            <!-- ══════════════════════════════
           SIDEBAR
      ═══════════════════════════════ -->
            <aside class="noticias-sidebar">

                <!-- Lo más leído -->
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fas fa-chart-line"></i> Lo más leído</h3>
                    <ol class="mas-leidas-list">
                        <?php foreach ($masLeidas as $i => $ml): ?>
                            <li class="mas-leida-item">
                                <span class="mas-leida-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <div class="mas-leida-info">
                                    <span class="mas-leida-cat" style="background:<?= catColor($ml['categoria']) ?>">
                                        <?= catLabel($ml['categoria']) ?>
                                    </span>
                                    <a href="<?= $base ?>/noticias/<?= $ml['slug'] ?>" class="mas-leida-titulo">
                                        <?= htmlspecialchars($ml['titulo']) ?>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>

                <!-- Boletín -->
                <div class="sidebar-widget widget-boletin">
                    <h3 class="widget-title">Boletín Informativo</h3>
                    <p class="widget-sub">Recibe las últimas noticias en tu correo.</p>
                    <form class="boletin-form" action="<?= $base ?>/boletin" method="POST">
                        <input type="email" name="email" placeholder="Tu email" required class="boletin-input">
                        <button type="submit" class="boletin-btn">Unirse</button>
                    </form>
                </div>

                <!-- Buscador -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Buscar noticia</h3>
                    <form action="<?= $base ?>/noticias" method="GET" class="search-form" id="searchForm">
                        <div class="search-input-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="q"
                                value="<?= htmlspecialchars($busqueda) ?>"
                                placeholder="Escribe tu búsqueda..."
                                class="search-input">
                        </div>
                    </form>
                </div>

                <!-- Categorías -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Categorías</h3>
                    <ul class="categorias-list">
                        <li>
                            <a href="<?= $base ?>/noticias" class="cat-link <?= $cat === '' ? 'active' : '' ?>">
                                <span class="cat-nombre">Todas</span>
                                <span class="cat-num"><?= $total ?></span>
                            </a>
                        </li>
                        <?php foreach ($categorias as $c): ?>
                            <li>
                                <a href="<?= $base ?>/noticias?cat=<?= $c['categoria'] ?>"
                                    class="cat-link <?= $cat === $c['categoria'] ? 'active' : '' ?>">
                                    <span class="cat-nombre"><?= catLabel($c['categoria']) ?></span>
                                    <span class="cat-num"><?= str_pad($c['total'], 2, '0', STR_PAD_LEFT) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Archivo histórico -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Archivo Histórico</h3>
                    <form action="<?= $base ?>/noticias" method="GET" id="archivoForm">
                        <select name="mes" class="archivo-select" id="archivoSelect">
                            <option value="">Seleccionar mes</option>
                            <?php foreach ($archivo as $a): ?>
                                <option value="<?= $a['mes'] ?>" <?= $mesFiltro === $a['mes'] ? 'selected' : '' ?>>
                                    <?= $a['label'] ?> (<?= $a['total'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down archivo-chevron"></i>
                    </form>
                </div>

                <!-- CTA Denuncia -->
                <div class="sidebar-widget widget-cta">
                    <div class="cta-icon"><i class="fas fa-bullhorn"></i></div>
                    <h3>¿Tienes alguna denuncia o sugerencia?</h3>
                    <p>Tu participación ciudadana es clave para un mejor tránsito.</p>
                    <a href="<?= $base ?>/contacto?tipo=denuncia" class="cta-btn">Contáctanos</a>
                </div>

            </aside>
            <!-- /sidebar -->

        </div><!-- /noticias-wrapper -->
    </div>
</div><!-- /noticias-layout -->

<!-- JS para AJAX suave sin recarga -->
<script>
    window.SITE_BASE = '<?= $base ?>';
</script>
<script src="<?= $base ?>/assets/js/noticias.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>