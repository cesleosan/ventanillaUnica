<?php
/**
 * TEMPORAL - Crear usuarios VUT separados del proyecto TIERRA
 * IMPORTANTE:
 * 1) Subir este archivo a /public/crear_usuarios_vut_temp.php
 * 2) Ejecutarlo una sola vez con: ?ejecutar=SI
 * 3) Borrarlo inmediatamente del servidor.
 *
 * Este script:
 * - Agrega columna usuarios.modulo si no existe.
 * - Marca usuarios existentes como TIERRA.
 * - Crea/actualiza SOLO usuarios con prefijo vut.* y modulo VUT.
 * - NO toca el usuario aGuillen existente.
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);

$EJECUTAR = (isset($_GET['ejecutar']) && strtoupper((string)$_GET['ejecutar']) === 'SI');

function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function normalizarNombreVUT(string $value): string {
    $value = trim($value);
    $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($normalizado === false) {
        $normalizado = $value;
    }
    $normalizado = strtoupper($normalizado);
    $normalizado = preg_replace('/\s+/', ' ', $normalizado);
    return trim((string)$normalizado);
}

function conectarVUT(): PDO {
    $configPath = __DIR__ . '/../app/config/config.php';

    if (file_exists($configPath)) {
        require_once $configPath;
    }

    $host = defined('DB_HOST') ? DB_HOST : 'localhost';
    $user = defined('DB_USER') ? DB_USER : 'admin_encuesta';
    $pass = defined('DB_PASS') ? DB_PASS : 'root';
    $name = defined('DB_NAME') ? DB_NAME : 'censo_tlalpan';

    $db = new PDO(
        "mysql:host={$host};dbname={$name};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    return $db;
}

function columnExists(PDO $db, string $table, string $column): bool {
    $stmt = $db->prepare("SELECT COUNT(*)
                          FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_SCHEMA = DATABASE()
                            AND TABLE_NAME = ?
                            AND COLUMN_NAME = ?");
    $stmt->execute([$table, $column]);
    return ((int)$stmt->fetchColumn()) > 0;
}

function indexExists(PDO $db, string $table, string $index): bool {
    $stmt = $db->prepare("SELECT COUNT(*)
                          FROM INFORMATION_SCHEMA.STATISTICS
                          WHERE TABLE_SCHEMA = DATABASE()
                            AND TABLE_NAME = ?
                            AND INDEX_NAME = ?");
    $stmt->execute([$table, $index]);
    return ((int)$stmt->fetchColumn()) > 0;
}

$usuarios = [
    [
        'nombre' => 'ADAN GUILLEN PE',
        'usuario' => 'vut.adan.guillen',
        'password' => 'Adan*VUT.26',
        'telefono' => '5500000000',
        'rol' => 'root',
        'modulo' => 'VUT',
        'nota' => 'ADMIN VUT / USUARIOS / ESTADISTICA'
    ],
    [
        'nombre' => 'BILLY MARTIN PINEDA SANCHEZ',
        'usuario' => 'vut.billy.pineda',
        'password' => 'Billy*VUT.26',
        'telefono' => '5500000000',
        'rol' => 'capturista',
        'modulo' => 'VUT',
        'nota' => 'CAPTURISTA VUT'
    ],
    [
        'nombre' => 'NANCY OLIVIA TRUJILLO VEGA',
        'usuario' => 'vut.nancy.trujillo',
        'password' => 'Nancy*VUT.26',
        'telefono' => '5500000000',
        'rol' => 'capturista',
        'modulo' => 'VUT',
        'nota' => 'CAPTURISTA VUT'
    ]
];

$rolesPermitidos = ['root', 'supervisor', 'consulta', 'encuestador', 'capturista'];

try {
    $db = conectarVUT();

    echo "<!doctype html><html lang='es'><head><meta charset='utf-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Crear usuarios VUT</title>";
    echo "<style>
        body{font-family:Arial,sans-serif;background:#f6f7fb;margin:0;padding:28px;color:#111827;}
        .card{max-width:1150px;margin:auto;background:#fff;border-radius:22px;padding:26px;box-shadow:0 18px 50px rgba(17,24,39,.08);border:1px solid #ead9e2;}
        h1{margin:0;color:#773357;font-size:28px;}
        .warn{background:#fff7ed;border-left:5px solid #f59e0b;padding:14px;border-radius:12px;margin:16px 0;font-weight:700;color:#92400e;}
        .ok{background:#ecfdf5;border-left:5px solid #10b981;padding:14px;border-radius:12px;margin:16px 0;font-weight:700;color:#065f46;}
        .danger{background:#fef2f2;border-left:5px solid #ef4444;padding:14px;border-radius:12px;margin:16px 0;font-weight:700;color:#991b1b;}
        table{width:100%;border-collapse:collapse;margin-top:18px;overflow:hidden;border-radius:14px;}
        th{background:#773357;color:#fff;text-align:left;padding:12px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;}
        td{border-bottom:1px solid #eee;padding:12px;font-size:13px;vertical-align:top;}
        code{background:#f3f4f6;padding:4px 7px;border-radius:7px;font-weight:700;}
        mark{background:#fef3c7;padding:4px 7px;border-radius:7px;font-weight:800;}
        .btn{display:inline-block;background:#773357;color:#fff;text-decoration:none;padding:12px 18px;border-radius:12px;font-weight:900;text-transform:uppercase;font-size:12px;margin-top:10px;}
    </style>";
    echo "</head><body><div class='card'>";

    echo "<h1>🛡️ Creación temporal de usuarios VUT</h1>";
    echo "<p>Base conectada: <code>" . h($db->query('SELECT DATABASE()')->fetchColumn()) . "</code></p>";

    if (!$EJECUTAR) {
        echo "<div class='warn'>Modo vista previa. Todavía NO se ha insertado ni actualizado nada.</div>";
        echo "<p>Para ejecutar realmente, abre:</p>";
        echo "<p><code>" . h(basename(__FILE__)) . "?ejecutar=SI</code></p>";
        echo "<a class='btn' href='?ejecutar=SI'>Ejecutar creación/actualización</a>";
    } else {
        echo "<div class='danger'>Ejecución activa. Al terminar, BORRA este archivo del servidor.</div>";

        if (!columnExists($db, 'usuarios', 'modulo')) {
            $db->exec("ALTER TABLE usuarios ADD COLUMN modulo VARCHAR(30) NOT NULL DEFAULT 'TIERRA' AFTER rol");
        }

        $db->exec("UPDATE usuarios SET modulo = 'TIERRA' WHERE modulo IS NULL OR modulo = '' OR modulo = 'GENERAL'");
        $db->exec("ALTER TABLE usuarios MODIFY COLUMN modulo VARCHAR(30) NOT NULL DEFAULT 'TIERRA'");

        if (!indexExists($db, 'usuarios', 'idx_usuarios_modulo')) {
            $db->exec("CREATE INDEX idx_usuarios_modulo ON usuarios(modulo)");
        }
    }

    echo "<table><tr><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th>Rol</th><th>Módulo</th><th>Resultado</th></tr>";

    foreach ($usuarios as $u) {
        $nombre = normalizarNombreVUT($u['nombre']);
        $usuario = trim($u['usuario']);
        $password = (string)$u['password'];
        $telefono = preg_replace('/\D/', '', (string)$u['telefono']);
        $rol = strtolower(trim($u['rol']));
        $modulo = strtoupper(trim($u['modulo']));

        if (!in_array($rol, $rolesPermitidos, true)) {
            throw new RuntimeException("Rol no permitido para {$usuario}: {$rol}");
        }

        $resultado = 'VISTA PREVIA';
        $color = '#f8fafc';

        if ($EJECUTAR) {
            $check = $db->prepare("SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1");
            $check->execute([':usuario' => $usuario]);
            $existe = $check->fetch();

            $hash = password_hash($password, PASSWORD_BCRYPT);

            if ($existe) {
                $stmt = $db->prepare("UPDATE usuarios
                                      SET password = :password,
                                          nombre_completo = :nombre,
                                          telefono = :telefono,
                                          rol = :rol,
                                          modulo = :modulo,
                                          activo = 1
                                      WHERE usuario = :usuario");
                $stmt->execute([
                    ':usuario' => $usuario,
                    ':password' => $hash,
                    ':nombre' => $nombre,
                    ':telefono' => $telefono,
                    ':rol' => $rol,
                    ':modulo' => $modulo
                ]);

                $resultado = 'YA EXISTÍA, SE ACTUALIZÓ';
                $color = '#fff7ed';
            } else {
                $stmt = $db->prepare("INSERT INTO usuarios (usuario, password, nombre_completo, telefono, rol, modulo, activo)
                                      VALUES (:usuario, :password, :nombre, :telefono, :rol, :modulo, 1)");
                $stmt->execute([
                    ':usuario' => $usuario,
                    ':password' => $hash,
                    ':nombre' => $nombre,
                    ':telefono' => $telefono,
                    ':rol' => $rol,
                    ':modulo' => $modulo
                ]);

                $resultado = 'CREADO CORRECTAMENTE';
                $color = '#ecfdf5';
            }
        }

        echo "<tr style='background:{$color}'>";
        echo "<td><b>" . h($nombre) . "</b><br><small>" . h($u['nota']) . "</small></td>";
        echo "<td><code>" . h($usuario) . "</code></td>";
        echo "<td><mark>" . h($password) . "</mark></td>";
        echo "<td><b>" . h($rol) . "</b></td>";
        echo "<td><b>" . h($modulo) . "</b></td>";
        echo "<td><b>" . h($resultado) . "</b></td>";
        echo "</tr>";
    }

    echo "</table>";

    if ($EJECUTAR) {
        echo "<div class='ok'>Usuarios VUT listos. Los existentes quedaron como TIERRA. Ahora prueba iniciar sesión y después elimina este archivo.</div>";
        echo "<p>Comando recomendado:</p><code>rm public/crear_usuarios_vut_temp.php</code>";
    }

    echo "</div></body></html>";

} catch (Throwable $e) {
    http_response_code(500);
    echo "<h2 style='color:red'>Error</h2>";
    echo "<pre>" . h($e->getMessage()) . "</pre>";
}
