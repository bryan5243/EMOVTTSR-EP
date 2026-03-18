<?php
// models/Noticia.php

class Noticia
{

    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ── Noticias paginadas con filtro de categoría opcional ──
    public function getPaginadas(int $pagina = 1, int $porPagina = 4, string $cat = ''): array
    {
        $offset = ($pagina - 1) * $porPagina;
        $sql    = "SELECT id, titulo, slug, resumen, imagen, categoria, fecha_publicacion, autor
               FROM noticias WHERE activo = 1";
        $params = [];
        if ($cat !== '') {
            $sql .= " AND categoria = :cat";
            $params[':cat'] = $cat;
        }
        $sql .= " ORDER BY fecha_publicacion DESC LIMIT :lim OFFSET :off";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Total para paginación ──
    public function contarTotal(string $cat = ''): int
    {
        $sql    = "SELECT COUNT(*) FROM noticias WHERE activo = 1";
        $params = [];
        if ($cat !== '') {
            $sql .= " AND categoria = :cat";
            $params[':cat'] = $cat;
        }
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Noticia destacada ──
    public function getDestacada(): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM noticias WHERE activo = 1 AND destacada = 1
       ORDER BY fecha_publicacion DESC LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    // ── Lo más leído ──
    public function getMasLeidas(int $limite = 4): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, titulo, slug, categoria FROM noticias
       WHERE activo = 1 ORDER BY visitas DESC LIMIT :lim"
        );
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Categorías con conteo ──
    public function getCategorias(): array
    {
        return $this->db->query(
            "SELECT categoria, COUNT(*) as total FROM noticias
       WHERE activo = 1 GROUP BY categoria ORDER BY total DESC"
        )->fetchAll();
    }

    // ── Archivo histórico ──
    public function getArchivo(): array
    {
        return $this->db->query(
            "SELECT DATE_FORMAT(fecha_publicacion,'%Y-%m') as mes,
              DATE_FORMAT(fecha_publicacion,'%M %Y')  as label,
              COUNT(*) as total
       FROM noticias WHERE activo = 1
       GROUP BY mes ORDER BY mes DESC"
        )->fetchAll();
    }

    // ── Búsqueda ──
    public function buscar(string $q, int $pagina = 1, int $porPagina = 4): array
    {
        $like   = '%' . $q . '%';
        $offset = ($pagina - 1) * $porPagina;
        $stmt   = $this->db->prepare(
            "SELECT id, titulo, slug, resumen, imagen, categoria, fecha_publicacion, autor
       FROM noticias WHERE activo = 1
         AND (titulo LIKE :q OR resumen LIKE :q2 OR contenido LIKE :q3)
       ORDER BY fecha_publicacion DESC LIMIT :lim OFFSET :off"
        );
        $stmt->bindValue(':q',  $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':q3', $like);
        $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarBusqueda(string $q): int
    {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM noticias WHERE activo = 1
       AND (titulo LIKE :q OR resumen LIKE :q2 OR contenido LIKE :q3)"
        );
        $stmt->bindValue(':q', $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':q3', $like);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Por mes archivo ──
    public function getPorMes(string $mes, int $pagina = 1, int $porPagina = 4): array
    {
        $offset = ($pagina - 1) * $porPagina;
        $stmt   = $this->db->prepare(
            "SELECT id, titulo, slug, resumen, imagen, categoria, fecha_publicacion, autor
       FROM noticias WHERE activo = 1
         AND DATE_FORMAT(fecha_publicacion,'%Y-%m') = :mes
       ORDER BY fecha_publicacion DESC LIMIT :lim OFFSET :off"
        );
        $stmt->bindValue(':mes', $mes);
        $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Noticia por slug (detalle) ──
    public function getPorSlug(string $slug): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM noticias WHERE slug = :slug AND activo = 1 LIMIT 1"
        );
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    // ── Noticia por ID ──
    public function getPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM noticias WHERE id = :id AND activo = 1 LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── Noticias relacionadas (misma categoría, excluyendo la actual) ──
    public function getRelacionadas(int $id, string $categoria, int $limite = 3): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, titulo, slug, resumen, imagen, categoria, fecha_publicacion
       FROM noticias
       WHERE activo = 1 AND categoria = :cat AND id != :id
       ORDER BY fecha_publicacion DESC
       LIMIT :lim"
        );
        $stmt->bindValue(':cat', $categoria);
        $stmt->bindValue(':id',  $id,     PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Anterior y siguiente (navegación) ──
    public function getAnterior(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT id, titulo, slug FROM noticias
       WHERE activo = 1 AND id < :id
       ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getSiguiente(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT id, titulo, slug FROM noticias
       WHERE activo = 1 AND id > :id
       ORDER BY id ASC LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── ADMIN: todas incluyendo inactivas ──
    public function getTodasAdmin(int $pagina = 1, int $porPagina = 15): array
    {
        $offset = ($pagina - 1) * $porPagina;
        $stmt   = $this->db->prepare(
            "SELECT id, titulo, slug, categoria, autor, destacada, activo, fecha_publicacion, visitas
       FROM noticias
       ORDER BY created_at DESC
       LIMIT :lim OFFSET :off"
        );
        $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarTotalAdmin(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
    }

    // ── ADMIN: crear noticia ──
    public function crear(array $datos): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO noticias
         (titulo, slug, resumen, contenido, imagen, categoria, autor, destacada, activo, fecha_publicacion, created_by)
       VALUES
         (:titulo, :slug, :resumen, :contenido, :imagen, :categoria, :autor, :destacada, :activo, :fecha, :user)"
        );
        $stmt->execute([
            ':titulo'     => $datos['titulo'],
            ':slug'       => $datos['slug'],
            ':resumen'    => $datos['resumen'],
            ':contenido'  => $datos['contenido'],
            ':imagen'     => $datos['imagen']     ?? null,
            ':categoria'  => $datos['categoria'],
            ':autor'      => $datos['autor']      ?? 'Comunicación Social',
            ':destacada'  => $datos['destacada']  ?? 0,
            ':activo'     => $datos['activo']     ?? 1,
            ':fecha'      => $datos['fecha_publicacion'],
            ':user'       => $datos['created_by'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // ── ADMIN: actualizar noticia ──
    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE noticias SET
         titulo = :titulo, slug = :slug, resumen = :resumen,
         contenido = :contenido, categoria = :categoria,
         autor = :autor, destacada = :destacada, activo = :activo,
         fecha_publicacion = :fecha
       WHERE id = :id"
        );
        return $stmt->execute([
            ':titulo'    => $datos['titulo'],
            ':slug'      => $datos['slug'],
            ':resumen'   => $datos['resumen'],
            ':contenido' => $datos['contenido'],
            ':categoria' => $datos['categoria'],
            ':autor'     => $datos['autor']     ?? 'Comunicación Social',
            ':destacada' => $datos['destacada'] ?? 0,
            ':activo'    => $datos['activo']    ?? 1,
            ':fecha'     => $datos['fecha_publicacion'],
            ':id'        => $id,
        ]);
    }

    // ── ADMIN: actualizar imagen ──
    public function actualizarImagen(int $id, string $imagen): bool
    {
        return $this->db->prepare("UPDATE noticias SET imagen = :img WHERE id = :id")
            ->execute([':img' => $imagen, ':id' => $id]);
    }

    // ── ADMIN: eliminar (soft delete) ──
    public function desactivar(int $id): bool
    {
        return $this->db->prepare("UPDATE noticias SET activo = 0 WHERE id = :id")
            ->execute([':id' => $id]);
    }

    // ── ADMIN: eliminar definitivo ──
    public function eliminar(int $id): bool
    {
        return $this->db->prepare("DELETE FROM noticias WHERE id = :id")
            ->execute([':id' => $id]);
    }

    // ── ADMIN: toggle destacada ──
    public function toggleDestacada(int $id): bool
    {
        return $this->db->prepare(
            "UPDATE noticias SET destacada = IF(destacada=1, 0, 1) WHERE id = :id"
        )->execute([':id' => $id]);
    }

    // ── Helper: generar slug único ──
    public function generarSlug(string $titulo, int $excluirId = 0): string
    {
        $slug = strtolower(trim($titulo));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        $slug = trim($slug, '-');

        $base  = $slug;
        $i     = 1;
        while (true) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM noticias WHERE slug = :slug AND id != :id"
            );
            $stmt->execute([':slug' => $slug, ':id' => $excluirId]);
            if ((int)$stmt->fetchColumn() === 0) break;
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    // ── Incrementar visitas ──
    public function incrementarVisitas(int $id): void
    {
        $this->db->prepare("UPDATE noticias SET visitas = visitas + 1 WHERE id = :id")
            ->execute([':id' => $id]);
    }
}
