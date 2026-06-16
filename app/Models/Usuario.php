<?php

class Usuario {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function limpiar($value): ?string {
        if ($value === null) return null;
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function mayusSinAcentos($value): string {
        $value = trim((string)$value);
        $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($normalizado === false) $normalizado = $value;
        $normalizado = strtoupper($normalizado);
        $normalizado = preg_replace('/\s+/', ' ', $normalizado);
        return trim((string)$normalizado);
    }

    public function rolesPermitidos(): array {
        return ['root', 'supervisor', 'consulta', 'encuestador', 'capturista'];
    }

    public function listar(string $q = ''): array {
        $params = [];
        $where = '';

        if (trim($q) !== '') {
            $where = "WHERE usuario LIKE ? OR nombre_completo LIKE ? OR telefono LIKE ? OR rol LIKE ?";
            $like = '%' . trim($q) . '%';
            $params = [$like, $like, $like, $like];
        }

        $sql = "SELECT id, usuario, nombre_completo, telefono, rol, activo, ultimo_acceso, created_at
                FROM usuarios
                {$where}
                ORDER BY activo DESC, nombre_completo ASC, id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtener(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, usuario, nombre_completo, telefono, rol, activo, ultimo_acceso, created_at FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function guardar(array $data): array {
        $id = (int)($data['id'] ?? 0);
        $usuario = $this->limpiar($data['usuario'] ?? null);
        $nombre = $this->mayusSinAcentos($data['nombre_completo'] ?? '');
        $telefono = preg_replace('/\D/', '', (string)($data['telefono'] ?? ''));
        $rol = strtolower(trim((string)($data['rol'] ?? 'capturista')));
        $activo = isset($data['activo']) ? (int)!!$data['activo'] : 1;
        $password = (string)($data['password'] ?? '');

        if ($usuario === null || strlen($usuario) < 3) {
            return ['success' => false, 'error' => 'El usuario debe tener al menos 3 caracteres.'];
        }

        if (!preg_match('/^[A-Za-z0-9._-]+$/', $usuario)) {
            return ['success' => false, 'error' => 'El usuario solo puede tener letras, números, punto, guion y guion bajo.'];
        }

        if ($nombre === '') {
            return ['success' => false, 'error' => 'El nombre completo es obligatorio.'];
        }

        if (!in_array($rol, $this->rolesPermitidos(), true)) {
            return ['success' => false, 'error' => 'Rol no permitido.'];
        }

        if ($telefono === '') {
            $telefono = null;
        } elseif (strlen($telefono) !== 10) {
            return ['success' => false, 'error' => 'El teléfono debe tener 10 dígitos.'];
        }

        try {
            if ($id > 0) {
                $actual = $this->obtener($id);
                if (!$actual) {
                    return ['success' => false, 'error' => 'El usuario no existe.'];
                }

                $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ? AND id <> ?");
                $stmt->execute([$usuario, $id]);
                if ((int)$stmt->fetchColumn() > 0) {
                    return ['success' => false, 'error' => 'Ese nombre de usuario ya existe.'];
                }

                $sets = ['usuario = ?', 'nombre_completo = ?', 'telefono = ?', 'rol = ?', 'activo = ?'];
                $params = [$usuario, $nombre, $telefono, $rol, $activo];

                if (trim($password) !== '') {
                    if (strlen($password) < 6) {
                        return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres.'];
                    }
                    $sets[] = 'password = ?';
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }

                $params[] = $id;
                $sql = 'UPDATE usuarios SET ' . implode(', ', $sets) . ' WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);

                return ['success' => true, 'id' => $id, 'updated' => true];
            }

            if (strlen($password) < 6) {
                return ['success' => false, 'error' => 'La contraseña inicial debe tener al menos 6 caracteres.'];
            }

            $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            if ((int)$stmt->fetchColumn() > 0) {
                return ['success' => false, 'error' => 'Ese nombre de usuario ya existe.'];
            }

            $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, password, nombre_completo, telefono, rol, activo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $usuario,
                password_hash($password, PASSWORD_DEFAULT),
                $nombre,
                $telefono,
                $rol,
                $activo
            ]);

            return ['success' => true, 'id' => (int)$this->db->lastInsertId(), 'created' => true];
        } catch (Throwable $e) {
            error_log('ERROR Usuario::guardar(): ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function cambiarActivo(int $id, int $activo): array {
        if ($id <= 0) {
            return ['success' => false, 'error' => 'ID inválido.'];
        }

        $stmt = $this->db->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
        $stmt->execute([$activo ? 1 : 0, $id]);

        return ['success' => true, 'id' => $id, 'activo' => $activo ? 1 : 0];
    }
}
