<?php
$page_title  = 'Inicio';
$active_page = 'inicio';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-bg-img"></div>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-shield-alt"></i>
                Institución al servicio del cantón
            </div>
            <h1 class="hero-title">
                EMOVTT
                <span>Santa Rosa</span>
            </h1>
            <p class="hero-subtitle">
                Innovando en la gestión de tránsito para una movilidad más segura, inclusiva y transparente en el corazón de El Oro.
            </p>
            <div class="hero-actions">
                <a href="<?= $base ?>/agendar-turno" class="btn-amarillo">
                    <i class="far fa-calendar-alt"></i> Agendar Turno
                </a>
                <a href="<?= $base ?>/servicios" class="btn-outline-blanco">
                    Ver Servicios
                </a>
            </div>
        </div>
    </div>
    <div class="hero-scroll"><i class="fas fa-chevron-down"></i></div>
</section>

<!-- ===== SERVICIOS ===== -->
<section class="servicios-wrap">
    <div class="container">
        <div class="sec-head" data-aos="fade-up">
            <h2 class="sec-title">Nuestros <span>Servicios</span></h2>
            <p class="sec-sub">Accede de forma rápida y sencilla a todos nuestros servicios digitales e institucionales.</p>
        </div>
        <div class="servicios-grid">

            <div class="servicio-card" data-aos="fade-up" data-aos-delay="0"
                onclick="location.href='<?= $base ?>/matriculacion'">
                <div class="srv-icon az"><i class="fas fa-car"></i></div>
                <h3>Matriculación</h3>
                <p>Consulta procesos, requisitos y agenda tu cita para la revisión técnica vehicular.</p>
                <a href="<?= $base ?>/matriculacion" class="srv-link">Ir al trámite <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="servicio-card" data-aos="fade-up" data-aos-delay="100"
                onclick="location.href='<?= $base ?>/multas'">
                <div class="srv-icon am"><i class="fas fa-file-invoice-dollar"></i></div>
                <h3>Consulta de Multas</h3>
                <p>Verifica infracciones pendientes y revisa tu historial vehicular actualizado.</p>
                <a href="<?= $base ?>/multas" class="srv-link">Consultar ahora <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="servicio-card" data-aos="fade-up" data-aos-delay="200"
                onclick="location.href='<?= $base ?>/titulos'">
                <div class="srv-icon ve"><i class="fas fa-id-card"></i></div>
                <h3>Títulos Habilitantes</h3>
                <p>Gestión de permisos para operadoras de transporte público y comercial del cantón.</p>
                <a href="<?= $base ?>/titulos" class="srv-link">Más información <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="servicio-card" data-aos="fade-up" data-aos-delay="300"
                onclick="location.href='<?= $base ?>/educacion-vial'">
                <div class="srv-icon ro"><i class="fas fa-graduation-cap"></i></div>
                <h3>Educación Vial</h3>
                <p>Programas de capacitación y concienciación para conductores y peatones de Santa Rosa.</p>
                <a href="<?= $base ?>/educacion-vial" class="srv-link">Ver programas <i class="fas fa-arrow-right"></i></a>
            </div>

        </div>
    </div>
</section>

<!-- ===== SERT-SR ===== -->
<section class="sert-section">
    <div class="container">
        <div class="sert-grid">

            <!-- Visual izquierda — imagen de fondo real -->
            <div class="sert-visual" data-aos="fade-right" data-aos-duration="900">
                <div class="sert-bg"></div>
                <div class="sert-bg-overlay"></div>
                <div class="sert-card-inner">
                    <div class="sert-badge-pill" data-aos="zoom-in" data-aos-delay="150">
                        <i class="fas fa-circle" style="font-size:7px"></i> Sistema Rotativo
                    </div>
                    <div class="sert-logo-txt" data-aos="fade-up" data-aos-delay="220">SERT-SR</div>
                    <p class="sert-sub-txt" data-aos="fade-up" data-aos-delay="300">Gestión Inteligente de Parqueo</p>
                </div>
            </div>

            <!-- Contenido derecha -->
            <div class="sert-content" data-aos="fade-left" data-aos-duration="900">
                <h2 class="sert-title" data-aos="fade-up" data-aos-delay="100">
                    Optimiza tu tiempo en la<br>Zona Regenerada
                </h2>
                <p class="sert-desc" data-aos="fade-up" data-aos-delay="180">
                    El Sistema de Estacionamiento Rotativo Tarifado garantiza la disponibilidad de espacios y mejora la fluidez vehicular en el centro de Santa Rosa.
                </p>

                <div class="sert-features">
                    <div class="sert-feat" data-aos="zoom-in-up" data-aos-delay="0">
                        <div class="sert-feat-ico az"><i class="fas fa-credit-card"></i></div>
                        <div>
                            <strong>Venta de Tarjetas</strong>
                            <span>Adquiere tu tarjeta en puntos de venta autorizados.</span>
                        </div>
                    </div>
                    <div class="sert-feat" data-aos="zoom-in-up" data-aos-delay="80">
                        <div class="sert-feat-ico am"><i class="fas fa-mobile-alt"></i></div>
                        <div>
                            <strong>Pagos Digitales</strong>
                            <span>Paga desde tu móvil cómodamente.</span>
                        </div>
                    </div>
                    <div class="sert-feat" data-aos="zoom-in-up" data-aos-delay="160">
                        <div class="sert-feat-ico ve"><i class="far fa-clock"></i></div>
                        <div>
                            <strong>Horario de Control</strong>
                            <span>Lun – Vie: 08:00 – 17:00.</span>
                        </div>
                    </div>
                    <div class="sert-feat" data-aos="zoom-in-up" data-aos-delay="240">
                        <div class="sert-feat-ico ro"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <strong>Evita Sanciones</strong>
                            <span>Respeta los límites establecidos.</span>
                        </div>
                    </div>
                </div>

                <div class="sert-actions" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?= $base ?>/sert" class="btn-sert-pri">
                        <i class="fas fa-map-marked-alt"></i> Ver Zonas de Parqueo
                    </a>
                    <a href="<?= $base ?>/sert/app" class="btn-sert-sec">
                        <i class="fas fa-download"></i> Descargar App Móvil
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== TERMINAL TERRESTRE ===== -->
<section class="terminal-section">
    <div class="container">
        <div class="terminal-grid">

            <!-- Info izquierda -->
            <div data-aos="fade-right" data-aos-duration="900">
                <span class="terminal-tag" data-aos="fade-down" data-aos-delay="80">Infraestructura Regional</span>
                <h2 class="terminal-title" data-aos="fade-up" data-aos-delay="140">
                    Terminal<br>Terrestre<br>
                    <strong>Santa Rosa</strong>
                </h2>
                <p class="terminal-desc" data-aos="fade-up" data-aos-delay="200">
                    Conectamos a Santa Rosa con todo el país a través de servicios modernos, seguros y una infraestructura diseñada para el viajero de hoy.
                </p>
                <div class="terminal-cards">
                    <div class="terminal-mini" data-aos="zoom-in-up" data-aos-delay="0">
                        <div class="terminal-mini-ico"><i class="fas fa-bus"></i></div>
                        <strong>Frecuencias</strong>
                        <span>Horarios de salida y destinos desde Santa Rosa hacia todos los cooperativas</span>
                        <a href="<?= $base ?>/terminal/frecuencias" class="terminal-lnk">Ver más <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="terminal-mini" data-aos="zoom-in-up" data-aos-delay="120">
                        <div class="terminal-mini-ico" style="color:var(--amarillo)"><i class="fas fa-box"></i></div>
                        <strong>Encomiendas</strong>
                        <span>Servicio de envíos seguros con todas las cooperativas presentes</span>
                        <a href="<?= $base ?>/terminal/encomiendas" class="terminal-lnk">Saber más <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Locales card derecha -->
            <div data-aos="fade-left" data-aos-duration="900">
                <div class="locales-card">
                    <div class="locales-hdr" data-aos="fade-up" data-aos-delay="100">
                        <i class="fas fa-store"></i>
                        <h3>Locales Comerciales</h3>
                    </div>

                    <div class="local-row" data-aos="fade-up" data-aos-delay="160">
                        <div class="local-ico ve"><i class="fas fa-utensils"></i></div>
                        <div class="local-info">
                            <strong>Patio de Comidas</strong>
                            <span>Restaurantes y cafeterías</span>
                        </div>
                        <span class="local-badge ve">ABIERTO</span>
                    </div>

                    <div class="local-row" data-aos="fade-up" data-aos-delay="220">
                        <div class="local-ico az"><i class="fas fa-university"></i></div>
                        <div class="local-info">
                            <strong>Servicios Bancarios</strong>
                            <span>ATM y servicios financieros</span>
                        </div>
                        <span class="local-badge ve">ABIERTO</span>
                    </div>

                    <div class="local-row" data-aos="fade-up" data-aos-delay="280">
                        <div class="local-ico am"><i class="fas fa-pills"></i></div>
                        <div class="local-info">
                            <strong>Farmacia 24h</strong>
                            <span>Atención permanente</span>
                        </div>
                        <span class="local-badge am">24 HRS</span>
                    </div>

                    <a href="<?= $base ?>/terminal/locales" class="btn-locales" data-aos="fade-up" data-aos-delay="340">
                        <i class="fas fa-th-large"></i> Conoce nuestras instalaciones
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== NOTICIAS ===== -->
<section class="noticias-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div>
                <h2 class="sec-title-left">Noticias & <span>Actualidad</span></h2>
                <p class="sec-sub-left">Entérate de las últimas acciones y proyectos que ejecutamos para mejorar la movilidad de nuestro cantón.</p>
            </div>
            <a href="<?= $base ?>/noticias" class="ver-mas" data-aos="fade-left" data-aos-delay="200">
                Explorar todas las noticias <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="noticias-grid">
            <?php
            $noticias = [
                [
                    'categoria' => 'institucional',
                    'cat_label' => 'Institucional',
                    'fecha' => '15 Oct, 2023',
                    'tiempo' => '5 min',
                    'titulo' => 'Iniciamos operativos preventivos de seguridad vial en el centro',
                    'resumen' => 'El cuerpo de agentes civiles de tránsito intensificó controles para reducir la congestión vehicular...',
                    'imagen' => 'https://images.unsplash.com/photo-1556742502-ec7c0e9f34b1?w=600&q=80',
                    'slug' => 'operativos-seguridad-vial'
                ],
                [
                    'categoria' => 'tramites',
                    'cat_label' => 'Trámites',
                    'fecha' => '12 Oct, 2023',
                    'tiempo' => '3 min',
                    'titulo' => 'Nueva plataforma digital para trámites de títulos habilitantes',
                    'resumen' => 'El nuevo portal transaccional permitirá agilizar los trámites administrativos de forma remota...',
                    'imagen' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&q=80',
                    'slug' => 'plataforma-digital-titulos'
                ],
                [
                    'categoria' => 'proyectos',
                    'cat_label' => 'Proyectos',
                    'fecha' => '08 Oct, 2023',
                    'tiempo' => '4 min',
                    'titulo' => 'Presentación del Plan Maestro de Movilidad Sostenible 2024',
                    'resumen' => 'Socializamos con la comunidad los ejes estratégicos para el desarrollo de nuevas ciclovías...',
                    'imagen' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=600&q=80',
                    'slug' => 'plan-maestro-movilidad'
                ],
            ];
            $delay = 0;
            foreach ($noticias as $n): ?>
                <article class="noticia-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>" data-aos-duration="900">
                    <div class="noticia-img">
                        <img src="<?= $n['imagen'] ?>" alt="<?= htmlspecialchars($n['titulo']) ?>" loading="lazy">
                        <span class="noticia-cat cat-<?= $n['categoria'] ?>"><?= $n['cat_label'] ?></span>
                    </div>
                    <div class="noticia-body">
                        <div class="noticia-meta">
                            <span><i class="far fa-calendar"></i> <?= $n['fecha'] ?></span>
                            <span><i class="far fa-clock"></i> <?= $n['tiempo'] ?></span>
                        </div>
                        <h3><a href="<?= $base ?>/noticias/<?= $n['slug'] ?>"><?= htmlspecialchars($n['titulo']) ?></a></h3>
                        <p><?= htmlspecialchars($n['resumen']) ?></p>
                        <a href="<?= $base ?>/noticias/<?= $n['slug'] ?>" class="srv-link" style="margin-top:14px;display:inline-flex;">
                            Leer más <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php $delay += 120;
            endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== ESTADÍSTICAS ===== -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-num">15k<span class="plus">+</span></div>
                <div class="stat-line"></div>
                <div class="stat-label">Vehículos Revisados</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="80">
                <div class="stat-num">24<span class="plus">/7</span></div>
                <div class="stat-line"></div>
                <div class="stat-label">Atención Ciudadana</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="160">
                <div class="stat-num">98<span class="plus">%</span></div>
                <div class="stat-line"></div>
                <div class="stat-label">Satisfacción</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="240">
                <div class="stat-num">12</div>
                <div class="stat-line"></div>
                <div class="stat-label">Obras en Curso</div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>