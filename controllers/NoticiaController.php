<?php
// controllers/NoticiaController.php

class NoticiaController
{

    private Noticia $modelo;

    // Categorías válidas
    const CATEGORIAS = [
        'institucional'  => 'Institucional',
        'seguridad-vial' => 'Seguridad Vial',
        'tramites'       => 'Trámites',
        'cooperativas'   => 'Cooperativas',
        'proyectos'      => 'Proyectos',
        'eventos'        => 'Eventos',
    ];

    // Config subida de imágenes
    const IMG_DIR      = 'uploads/noticias/';
    const IMG_MAX_KB   = 2048;   // 2 MB
    const IMG_TIPOS    = ['image/jpeg', 'image/png', 'image/webp'];
    const IMG_EXT      = ['jpg', 'jpeg', 'png', 'webp'];

    public function __construct()
    {
        require_once __DIR__ . '/../models/Noticia.php';
        $this->modelo = new Noticia();
    }

  // ────────────────────────────────────────────
  //  FRONTEND
  // ────────────────────────────────────────────

    /** Datos completos para pages/noticias.php */
    public function lista(): array
    {
        $porPagina  = 4;
        $pagina     = max(1, (int)($_GET['pagina'] ?? 1));
        $cat        = $this->sanitizarCat($_GET['cat']    ?? '');
        $busqueda   = trim(strip_tags($_GET['q']           ?? ''));
        $mesFiltro  = preg_replace('/[^0-9\-]/', '', $_GET['mes'] ?? '');

        if ($busqueda !== '') {
            $noticias = $this->modelo->buscar($busqueda, $pagina, $porPagina);
            $total    = $this->modelo->contarBusqueda($busqueda);
        } elseif ($mesFiltro !== '') {
            $noticias = $this->modelo->getPorMes($mesFiltro, $pagina, $porPagina);
            $total    = count($noticias); // simple
        } else {
            $noticias = $this->modelo->getPaginadas($pagina, $porPagina, $cat);
            $total    = $this->modelo->contarTotal($cat);
        }

        return [
            'noticias'      => $noticias,
            'total'         => $total,
            'pagina'        => $pagina,
            'totalPaginas'  => (int) ceil($total / $porPagina),
            'cat'           => $cat,
            'busqueda'      => $busqueda,
            'mesFiltro'     => $mesFiltro,
            'destacada'     => $this->modelo->getDestacada(),
            'masLeidas'     => $this->modelo->getMasLeidas(4),
            'categorias'    => $this->modelo->getCategorias(),
            'archivo'       => $this->modelo->getArchivo(),
        ];
    }

    /** Datos para pages/noticia-detalle.php */
    public function detalle(string $slug): array
    {
        $noticia = $this->modelo->getPorSlug($slug);
        if (!$noticia) {
            http_response_code(404);
            return ['noticia' => null];
        }

        // Incrementar visitas (solo una vez por sesión)
        $key = 'vista_' . $noticia['id'];
        if (empty($_SESSION[$key])) {
            $this->modelo->incrementarVisitas($noticia['id']);
            $_SESSION[$key] = true;
        }

        return [
            'noticia'      => $noticia,
            'relacionadas' => $this->modelo->getRelacionadas($noticia['id'], $noticia['categoria']),
            'anterior'     => $this->modelo->getAnterior($noticia['id']),
            'siguiente'    => $this->modelo->getSiguiente($noticia['id']),
            'masLeidas'    => $this->modelo->getMasLeidas(4),
            'categorias'   => $this->modelo->getCategorias(),
        ];
    }

  // ────────────────────────────────────────────
  //  ADMIN — CRUD
  // ────────────────────────────────────────────

    /** Lista para panel admin */
    public function listaAdmin(): array
    {
        $pagina    = max(1, (int)($_GET['p'] ?? 1));
        $porPagina = 15;
        return [
            'noticias'     => $this->modelo->getTodasAdmin($pagina, $porPagina),
            'total'        => $this->modelo->contarTotalAdmin(),
            'pagina'       => $pagina,
            'totalPaginas' => (int) ceil($this->modelo->contarTotalAdmin() / $porPagina),
            'categorias'   => self::CATEGORIAS,
        ];
    }

    /** Guardar nueva noticia (POST desde admin) */
    public function guardar(array $post, array $files = []): array
    {
        $errores = $this->validar($post);
        if (!empty($errores)) return ['ok' => false, 'errores' => $errores];

        $datos = [
            'titulo'           => trim($post['titulo']),
            'slug'             => $this->modelo->generarSlug(trim($post['titulo'])),
            'resumen'          => trim($post['resumen']),
            'contenido'        => $post['contenido'],
            'categoria'        => $post['categoria'],
            'autor'            => trim($post['autor'] ?? 'Comunicación Social'),
            'destacada'        => isset($post['destacada']) ? 1 : 0,
            'activo'           => isset($post['activo'])    ? 1 : 0,
            'fecha_publicacion' => $post['fecha_publicacion'],
            'created_by'       => $_SESSION['admin_id'] ?? null,
        ];

        // Subir imagen si viene
        if (!empty($files['imagen']['name'])) {
            $subida = $this->subirImagen($files['imagen']);
            if (!$subida['ok']) return ['ok' => false, 'errores' => ['imagen' => $subida['error']]];
            $datos['imagen'] = $subida['nombre'];
        }

        $id = $this->modelo->crear($datos);
        return ['ok' => true, 'id' => $id, 'slug' => $datos['slug']];
    }

    /** Actualizar noticia existente (POST desde admin) */
    public function actualizar(int $id, array $post, array $files = []): array
    {
        $errores = $this->validar($post);
        if (!empty($errores)) return ['ok' => false, 'errores' => $errores];

        $datos = [
            'titulo'           => trim($post['titulo']),
            'slug'             => $this->modelo->generarSlug(trim($post['titulo']), $id),
            'resumen'          => trim($post['resumen']),
            'contenido'        => $post['contenido'],
            'categoria'        => $post['categoria'],
            'autor'            => trim($post['autor'] ?? 'Comunicación Social'),
            'destacada'        => isset($post['destacada']) ? 1 : 0,
            'activo'           => isset($post['activo'])    ? 1 : 0,
            'fecha_publicacion' => $post['fecha_publicacion'],
        ];

        $ok = $this->modelo->actualizar($id, $datos);

        // Actualizar imagen si viene nueva
        if (!empty($files['imagen']['name'])) {
            $subida = $this->subirImagen($files['imagen']);
            if ($subida['ok']) {
                // Eliminar imagen anterior
                $vieja = $this->modelo->getPorId($id);
                if ($vieja && $vieja['imagen']) {
                    $ruta = BASE_PATH . '/' . self::IMG_DIR . $vieja['imagen'];
                    if (file_exists($ruta)) unlink($ruta);
                }
                $this->modelo->actualizarImagen($id, $subida['nombre']);
            }
        }

        return ['ok' => $ok, 'id' => $id];
    }

    /** Eliminar imagen de una noticia */
    public function eliminarImagen(int $id): bool
    {
        $noticia = $this->modelo->getPorId($id);
        if (!$noticia || !$noticia['imagen']) return false;

        $ruta = BASE_PATH . '/' . self::IMG_DIR . $noticia['imagen'];
        if (file_exists($ruta)) unlink($ruta);
        return $this->modelo->actualizarImagen($id, '');
    }

    /** Toggle destacada vía AJAX */
    public function toggleDestacada(int $id): array
    {
        $ok      = $this->modelo->toggleDestacada($id);
        $noticia = $this->modelo->getPorId($id);
        return ['ok' => $ok, 'destacada' => (int)($noticia['destacada'] ?? 0)];
    }

    /** Eliminar noticia */
    public function eliminar(int $id): array
    {
        // Eliminar imagen primero
        $this->eliminarImagen($id);
        $ok = $this->modelo->eliminar($id);
        return ['ok' => $ok];
    }

  // ────────────────────────────────────────────
  //  HELPERS PRIVADOS
  // ────────────────────────────────────────────

    /** Subir imagen al servidor */
    private function subirImagen(array $file): array
    {
        // Verificar errores de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Error al subir el archivo.'];
        }

        // Verificar tamaño
        if ($file['size'] > self::IMG_MAX_KB * 1024) {
            return ['ok' => false, 'error' => 'La imagen no debe superar ' . self::IMG_MAX_KB . ' KB.'];
        }

        // Verificar tipo MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::IMG_TIPOS)) {
            return ['ok' => false, 'error' => 'Formato no permitido. Use JPG, PNG o WebP.'];
        }

        // Extensión segura
        $ext    = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };

        $nombre = uniqid('noticia_', true) . '.' . $ext;
        $destino = BASE_PATH . '/' . self::IMG_DIR . $nombre;

        // Crear carpeta si no existe
        if (!is_dir(BASE_PATH . '/' . self::IMG_DIR)) {
            mkdir(BASE_PATH . '/' . self::IMG_DIR, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            return ['ok' => false, 'error' => 'No se pudo guardar la imagen.'];
        }

        return ['ok' => true, 'nombre' => $nombre];
    }

    /** Validar campos del formulario */
    private function validar(array $post): array
    {
        $errores = [];
        if (empty(trim($post['titulo'] ?? '')))
            $errores['titulo'] = 'El título es obligatorio.';
        if (strlen(trim($post['titulo'] ?? '')) > 250)
            $errores['titulo'] = 'El título no debe superar 250 caracteres.';
        if (empty(trim($post['resumen'] ?? '')))
            $errores['resumen'] = 'El resumen es obligatorio.';
        if (empty($post['contenido'] ?? ''))
            $errores['contenido'] = 'El contenido es obligatorio.';
        if (!array_key_exists($post['categoria'] ?? '', self::CATEGORIAS))
            $errores['categoria'] = 'Selecciona una categoría válida.';
        if (empty($post['fecha_publicacion'] ?? ''))
            $errores['fecha'] = 'La fecha de publicación es obligatoria.';
        return $errores;
    }

    /** Sanitizar categoría de GET */
    private function sanitizarCat(string $cat): string
    {
        $cat = preg_replace('/[^a-z\-]/', '', strtolower($cat));
        return array_key_exists($cat, self::CATEGORIAS) ? $cat : '';
    }

    /** Label de categoría */
    public static function catLabel(string $cat): string
    {
        return self::CATEGORIAS[$cat] ?? ucfirst($cat);
    }

    /** Color de categoría */
    public static function catColor(string $cat): string
    {
        return match ($cat) {
            'tramites'       => '#F5A623',
            'seguridad-vial' => '#E74C3C',
            'cooperativas'   => '#27AE60',
            'institucional'  => '#1B2B8B',
            'proyectos'      => '#8E44AD',
            'eventos'        => '#2980B9',
            default          => '#6B7280',
        };
    }
}
