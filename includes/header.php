<?php
// includes/header.php
$page_title  = $page_title  ?? 'EMOVTT Santa Rosa';
$active_page = $active_page ?? 'inicio';

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | EMOVTT Santa Rosa</title>
    <meta name="description" content="Empresa Pública de Movilidad de Santa Rosa - Tránsito, transporte terrestre y seguridad vial.">

    <!-- AOS animaciones -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>

<body>

    <!-- AOS init -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 900,
            once: false,
            mirror: true,
            offset: 80,
            easing: 'ease-out-cubic'
        });
    </script>

    <!-- ===== TOPBAR ===== -->
    <div class="site-topbar">
        <div class="container">
            <div class="topbar-left">
                <span class="topbar-item"><i class="fas fa-phone"></i> (07) 2123-456</span>
                <span class="topbar-item"><i class="fas fa-clock"></i> LUN - VIE: 08:00 - 17:00</span>
            </div>
            <div class="topbar-right">
                <a href="<?= $base ?>/transparencia">TRANSPARENCIA LOTAIP</a>
                <a href="<?= $base ?>/directorio">DIRECTORIO</a>
                <a href="#"><i class="fas fa-share-alt"></i></a>
            </div>
        </div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <nav class="site-navbar" id="siteNavbar">
        <div class="nav-container">

            <!-- Logo -->
            <a href="<?= $base ?>/" class="nav-brand">
                <?php if (file_exists(__DIR__ . '/../assets/img/logo.png')): ?>
                    <img src="<?= $base ?>/assets/img/logo.png" alt="Logo EMOVTT" class="nav-logo">
                <?php else: ?>
                    <div class="logo-box">EMOVTT</div>
                <?php endif; ?>
                <div class="brand-text">
                    <strong>Empresa de Movilidad</strong>
                    Santa Rosa &ndash; EP
                </div>
            </a>

            <!-- Menú principal -->
            <ul class="nav-menu" id="navMenu">

                <!-- Inicio -->
                <li class="nav-item">
                    <a href="<?= $base ?>/" class="nav-link <?= $active_page === 'inicio' ? 'active' : '' ?>">
                        <i class="fas fa-home nav-icon"></i> Inicio
                    </a>
                </li>

                <!-- Noticias -->
                <li class="nav-item">
                    <a href="<?= $base ?>/noticias"
                        class="nav-link <?= $active_page === 'noticias' ? 'active' : '' ?>"
                        data-toggle="dropdown">
                        <i class="fas fa-newspaper nav-icon"></i> Noticias
                        <i class="fas fa-chevron-down chevron"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="<?= $base ?>/noticias">
                            <i class="fas fa-list"></i> Todas las noticias
                        </a>
                        <a href="<?= $base ?>/noticias?cat=institucional">
                            <i class="fas fa-building"></i> Institucional
                        </a>
                        <a href="<?= $base ?>/noticias?cat=seguridad-vial">
                            <i class="fas fa-shield-alt"></i> Seguridad Vial
                        </a>
                        <a href="<?= $base ?>/noticias?cat=tramites">
                            <i class="fas fa-file-alt"></i> Trámites
                        </a>
                        <a href="<?= $base ?>/noticias?cat=proyectos">
                            <i class="fas fa-project-diagram"></i> Proyectos
                        </a>
                        <a href="<?= $base ?>/noticias?cat=eventos">
                            <i class="fas fa-calendar-star"></i> Eventos
                        </a>
                    </div>
                </li>

                <!-- Transparencia -->
                <li class="nav-item">
                    <a href="<?= $base ?>/transparencia"
                        class="nav-link <?= $active_page === 'transparencia' ? 'active' : '' ?>"
                        data-toggle="dropdown">
                        <i class="fas fa-eye nav-icon"></i> Transparencia
                        <i class="fas fa-chevron-down chevron"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="<?= $base ?>/transparencia">
                            <i class="fas fa-file-contract"></i> LOTAIP
                        </a>
                        <a href="<?= $base ?>/rendicion-cuentas">
                            <i class="fas fa-chart-bar"></i> Rendición de Cuentas
                        </a>
                        <a href="<?= $base ?>/poa-pac">
                            <i class="fas fa-clipboard-list"></i> POA / PAC
                        </a>
                    </div>
                </li>

                <!-- Cooperativas / Terminal -->
                <li class="nav-item">
                    <a href="<?= $base ?>/cooperativas"
                        class="nav-link <?= $active_page === 'cooperativas' ? 'active' : '' ?>"
                        data-toggle="dropdown">
                        <i class="fas fa-bus nav-icon"></i> Cooperativas
                        <i class="fas fa-chevron-down chevron"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="<?= $base ?>/cooperativas">
                            <i class="fas fa-bus-alt"></i> Cooperativas de Buses
                        </a>
                        <a href="<?= $base ?>/terminal">
                            <i class="fas fa-building"></i> Terminal Terrestre
                        </a>
                        <a href="<?= $base ?>/frecuencias">
                            <i class="fas fa-clock"></i> Frecuencias y Horarios
                        </a>
                    </div>
                </li>

                <!-- Matriculación -->
                <li class="nav-item">
                    <a href="<?= $base ?>/matriculacion"
                        class="nav-link <?= $active_page === 'matriculacion' ? 'active' : '' ?>"
                        data-toggle="dropdown">
                        <i class="fas fa-car nav-icon"></i> Matriculación
                        <i class="fas fa-chevron-down chevron"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="<?= $base ?>/matriculacion">
                            <i class="fas fa-info-circle"></i> Proceso de Matriculación
                        </a>
                        <a href="<?= $base ?>/matriculacion/requisitos">
                            <i class="fas fa-clipboard-check"></i> Requisitos
                        </a>
                        <a href="<?= $base ?>/matriculacion/agendar-turno">
                            <i class="far fa-calendar-alt"></i> Agendar Turno
                        </a>
                        <a href="<?= $base ?>/multas">
                            <i class="fas fa-file-invoice-dollar"></i> Consulta de Multas
                        </a>
                    </div>
                </li>

                <!-- Contactos -->
                <li class="nav-item">
                    <a href="<?= $base ?>/contacto"
                        class="nav-link <?= $active_page === 'contacto' ? 'active' : '' ?>"
                        data-toggle="dropdown">
                        <i class="fas fa-headset nav-icon"></i> Contactos
                        <i class="fas fa-chevron-down chevron"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="<?= $base ?>/contacto">
                            <i class="fas fa-envelope"></i> Contáctanos
                        </a>
                        <a href="<?= $base ?>/directorio">
                            <i class="fas fa-address-book"></i> Directorio Institucional
                        </a>
                        <a href="<?= $base ?>/nosotros">
                            <i class="fas fa-users"></i> Quiénes Somos
                        </a>
                    </div>
                </li>

            </ul>

            <!-- CTA + Hamburger -->
            <div class="nav-actions">
                <a href="<?= $base ?>/tramites" class="btn-azul nav-cta-hide">
                    <i class="fas fa-comment-dots"></i> Trámites en Línea
                </a>
                <button class="nav-hamburger" id="navHamburger" aria-label="Abrir menú" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </nav>