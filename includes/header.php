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

        <!-- Menú -->
        <ul class="nav-menu" id="navMenu">

            <li class="nav-item">
                <a href="<?= $base ?>/" class="nav-link <?= $active_page === 'inicio' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>
            </li>

            <!-- Dropdown -->
            <li class="nav-item">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <i class="fas fa-newspaper"></i> Noticias
                    <i class="fas fa-chevron-down chevron"></i>
                </a>

                <div class="nav-dropdown">
                    <a href="<?= $base ?>/noticias">Todas las noticias</a>
                    <a href="<?= $base ?>/noticias?cat=institucional">Institucional</a>
                    <a href="<?= $base ?>/noticias?cat=seguridad-vial">Seguridad Vial</a>
                    <a href="<?= $base ?>/noticias?cat=tramites">Trámites</a>
                    <a href="<?= $base ?>/noticias?cat=proyectos">Proyectos</a>
                    <a href="<?= $base ?>/noticias?cat=eventos">Eventos</a>
                </div>
            </li>

            <!-- Agrega los demás igual -->
        </ul>

        <!-- Acciones -->
        <div class="nav-actions">
            <button class="nav-hamburger" id="navHamburger">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</nav>

<!-- Overlay -->
<div class="nav-overlay" id="navOverlay"></div>