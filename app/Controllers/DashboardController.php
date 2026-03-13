<?php
// app/Controllers/DashboardController.php

class DashboardController {
    
    private function generateCaptchaBase64() {
        // 1. DICCIONARIO LIMPIO (Solo Mayúsculas y Números claros)
        // Quitamos la 'O', '0', 'I', '1' para evitar confusiones.
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        
        // 2. Generar código de 5 caracteres
        $code = '';
        $length = strlen($chars);
        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[rand(0, $length - 1)];
        }

        // Guardamos en sesión (Tal cual como se generó)
        $_SESSION['captcha_code'] = $code;

        if (!function_exists('imagecreatetruecolor')) return null;

        // 3. Crear Imagen
        $image = imagecreatetruecolor(130, 45); // Un poco más grande
        $bg_color = imagecolorallocate($image, 250, 250, 250); // Casi blanco
        $text_color = imagecolorallocate($image, 119, 51, 87); // Vino Institucional
        $line_color = imagecolorallocate($image, 200, 200, 200);

        imagefill($image, 0, 0, $bg_color);

        // Ruido
        for($i=0; $i<6; $i++) {
            imageline($image, 0, rand()%45, 130, rand()%45, $line_color);
        }

        // Dibujar Texto (Centrado y legible)
        // font 5 es la fuente built-in más grande de PHP GD
        imagestring($image, 5, 35, 14, $code, $text_color);

        ob_start();
        imagejpeg($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'login') {
                
                // --- VALIDACIÓN INTELIGENTE ---
                // 1. Obtenemos lo que escribió el usuario
                $inputUser = $_POST['captcha'] ?? '';
                // 2. Obtenemos el código real de la sesión
                $realCode = $_SESSION['captcha_code'] ?? '';

                // 3. Comparamos ambos convertidos a MAYÚSCULAS
                // Esto hace que 'aaaaa' sea igual a 'AAAAA' -> ¡Coherencia total!
                if (strtoupper($inputUser) !== strtoupper($realCode)) {
                    $error = "El código de seguridad no coincide.";
                } 
                elseif ($_POST['username'] === 'SISTEMAS' && !empty($_POST['password'])) {
                    $_SESSION['user'] = [
                        'name' => 'ADAN GUILLEN PE',
                        'role' => 'SISTEMAS'
                    ];
                    header("Location: /");
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }

            } elseif ($_POST['action'] === 'logout') {
                session_destroy();
                header("Location: /");
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