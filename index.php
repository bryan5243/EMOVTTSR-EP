<?php
// index.php — Router principal EMOVTT
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// ── Leer URI limpia y quitar el basePath si el sitio está en subcarpeta ──
$uri      = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$basePath = trim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = trim(substr($uri, strlen($basePath)), '/');
}

// Separar en segmentos limpios: /noticias/mi-slug → ['noticias','mi-slug']
$segs    = array_values(array_filter(explode('/', $uri)));
$seccion = strtolower(preg_replace('/[^a-z0-9\-]/', '', $segs[0] ?? ''));
$param   = preg_replace('/[^a-z0-9\-]/', '', $segs[1] ?? '');

// ────────────────────────────────────────────────────────
//  MAPA DE RUTAS  seccion => archivo
// ────────────────────────────────────────────────────────
$rutas = [
    // Inicio
    ''               => 'pages/home.php',
    'inicio'         => 'pages/home.php',

    // Noticias
    'noticias'       => 'pages/noticias.php',        // lista + detalle vía $param

    // Transparencia
    'transparencia'  => 'pages/transparencia.php',   // LOTAIP
    'rendicion-cuentas' => 'pages/rendicion-cuentas.php',
    'poa-pac'        => 'pages/poa-pac.php',

    // Cooperativas / Terminal
    'cooperativas'   => 'pages/cooperativas.php',
    'terminal'       => 'pages/terminal.php',
    'frecuencias'    => 'pages/frecuencias.php',

    // Matriculación
    'matriculacion'  => 'pages/matriculacion.php',
    'multas'         => 'pages/multas.php',

    // SERT
    'sert'           => 'pages/sert.php',

    // Institucional
    'nosotros'       => 'pages/nosotros.php',
    'directorio'     => 'pages/directorio.php',
    'contacto'       => 'pages/contacto.php',
    'tramites'       => 'pages/tramites.php',
    'servicios'      => 'pages/servicios.php',
];

// ────────────────────────────────────────────────────────
//  CASO ESPECIAL: sub-rutas con parámetro
//  /noticias/{slug}
//  /matriculacion/requisitos
//  /matriculacion/agendar-turno
// ────────────────────────────────────────────────────────
if ($seccion === 'noticias' && $param !== '') {
    // Detalle de noticia
    $slug = $param;
    $archivo = 'pages/noticia-detalle.php';
    if (file_exists(__DIR__ . '/' . $archivo)) {
        require __DIR__ . '/' . $archivo;
    } else {
        // Fallback: lista de noticias
        require __DIR__ . '/pages/noticias.php';
    }
    exit;
}

if ($seccion === 'matriculacion' && $param !== '') {
    // /matriculacion/requisitos  o  /matriculacion/agendar-turno
    $sub = [
        'requisitos'    => 'pages/matriculacion-requisitos.php',
        'agendar-turno' => 'pages/agendar-turno.php',
    ];
    $archivo = $sub[$param] ?? 'pages/matriculacion.php';
    $archivo = file_exists(__DIR__ . '/' . $archivo) ? $archivo : 'pages/matriculacion.php';
    require __DIR__ . '/' . $archivo;
    exit;
}

if ($seccion === 'rendicion-cuentas' && $param !== '') {
    // /rendicion-cuentas/2024
    $anio    = (int) preg_replace('/[^0-9]/', '', $param);
    $archivo = 'pages/rendicion-detalle.php';
    if ($anio > 0 && file_exists(__DIR__ . '/' . $archivo)) {
        require __DIR__ . '/' . $archivo;
    } else {
        require __DIR__ . '/pages/rendicion-cuentas.php';
    }
    exit;
}

// ────────────────────────────────────────────────────────
//  ROUTING NORMAL
// ────────────────────────────────────────────────────────
if (isset($rutas[$seccion])) {
    $archivo = __DIR__ . '/' . $rutas[$seccion];
    if (file_exists($archivo)) {
        require $archivo;
    } else {
        // Página aún no creada — mostrar placeholder
        require __DIR__ . '/pages/home.php';
    }
} else {
    http_response_code(404);
    require __DIR__ . '/pages/home.php';
}
