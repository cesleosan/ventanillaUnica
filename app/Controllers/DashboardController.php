<?php
// app/Controllers/DashboardController.php

class DashboardController {

    private $db = null;

    public function __construct() {
        require_once '../app/config/config.php';
        require_once '../app/Libraries/Database.php';

        $database = new \Database();
        $this->db = $database->getConnection();
    }

    private function generateCaptchaBase64() {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $code = '';
        $length = strlen($chars);

        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[random_int(0, $length - 1)];
        }

        $_SESSION['captcha_code'] = $code;

        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        $image = imagecreatetruecolor(130, 45);
        $bg_color = imagecolorallocate($image, 250, 250, 250);
        $text_color = imagecolorallocate($image, 119, 51, 87);
        $line_color = imagecolorallocate($image, 200, 200, 200);

        imagefill($image, 0, 0, $bg_color);

        for ($i = 0; $i < 6; $i++) {
            imageline($image, 0, random_int(0, 45), 130, random_int(0, 45), $line_color);
        }

        imagestring($image, 5, 35, 14, $code, $text_color);

        ob_start();
        imagejpeg($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }

    private function buscarUsuarioActivo(string $usuario): ?array {
        $stmt = $this->db->prepare("SELECT id, usuario, password, nombre_completo, telefono, rol, activo, ultimo_acceso, created_at
                                    FROM usuarios
                                    WHERE usuario = ?
                                    AND activo = 1
                                    AND modulo = 'VUT'
                                    LIMIT 1");
        $stmt->execute([$usuario]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function registrarAcceso(int $idUsuario): void {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
            $stmt->execute([$idUsuario]);
        } catch (Throwable $e) {
            error_log('No se pudo actualizar ultimo_acceso: ' . $e->getMessage());
        }
    }

    private function iniciarSesionUsuario(array $usuario): void {
        $_SESSION['user'] = [
            'id' => (int)$usuario['id'],
            'usuario' => $usuario['usuario'],
            'nombre' => $usuario['nombre_completo'],
            'name' => $usuario['nombre_completo'],
            'rol' => $usuario['rol'],
            'role' => $usuario['rol'],
            'telefono' => $usuario['telefono'] ?? null
        ];

        // Compatibilidad con código viejo que lee variables directas de sesión.
        $_SESSION['id_usuario'] = (int)$usuario['id'];
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['nombre'] = $usuario['nombre_completo'];
        $_SESSION['rol'] = $usuario['rol'];
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'login') {
                $inputUser = $_POST['captcha'] ?? '';
                $realCode = $_SESSION['captcha_code'] ?? '';

                if (strtoupper(trim($inputUser)) !== strtoupper(trim($realCode))) {
                    $error = 'El código de seguridad no coincide.';
                } else {
                    $username = trim((string)($_POST['username'] ?? ''));
                    $password = (string)($_POST['password'] ?? '');

                    if ($username === '' || $password === '') {
                        $error = 'Usuario y contraseña son obligatorios.';
                    } else {
                        $usuario = $this->buscarUsuarioActivo($username);

                        if (!$usuario || (int)$usuario['activo'] !== 1) {
                            $error = 'Usuario o contraseña incorrectos.';
                        } elseif (!password_verify($password, (string)$usuario['password'])) {
                            $error = 'Usuario o contraseña incorrectos.';
                        } else {
                            $this->iniciarSesionUsuario($usuario);
                            $this->registrarAcceso((int)$usuario['id']);

                            header('Location: /');
                            exit;
                        }
                    }
                }

            } elseif ($action === 'logout') {
                session_destroy();
                header('Location: /');
                exit;
            }
        }

        $isLoggedIn = isset($_SESSION['user']);

        $captchaImage = '';
        if (!$isLoggedIn) {
            $captchaImage = $this->generateCaptchaBase64();
        }

        $data = [
            'pageTitle' => 'Acceso Alcaldía Tlalpan',
            'user' => $isLoggedIn ? $_SESSION['user'] : null,
            'error' => $error,
            'captcha_image' => $captchaImage
        ];

        require_once '../app/Views/layouts/main.php';
    }
}
