<?php
// public/captcha.php
session_start();

// 1. Generar código aleatorio
$random_alpha = md5(rand());
$captcha_code = substr($random_alpha, 0, 6); // 6 caracteres
$_SESSION['captcha_code'] = $captcha_code; // Guardar en sesión para validar

// 2. Crear la imagen (Ancho 120, Alto 40)
$target_layer = imagecreatetruecolor(120, 40);

// 3. Colores (Usamos tus institucionales)
$bg_color = imagecolorallocate($target_layer, 245, 245, 245); // Gris claro
$text_color = imagecolorallocate($target_layer, 119, 51, 87); // #773357 (Vino)
$line_color = imagecolorallocate($target_layer, 156, 92, 127); // Acento

imagefill($target_layer, 0, 0, $bg_color);

// 4. Añadir "ruido" (líneas) para seguridad
for($i=0; $i<5; $i++) {
    imageline($target_layer, 0, rand() % 40, 120, rand() % 40, $line_color);
}

// 5. Escribir el código en la imagen
imagestring($target_layer, 5, 30, 12, $captcha_code, $text_color);

// 6. Renderizar
header("Content-type: image/jpeg");
imagejpeg($target_layer);
imagedestroy($target_layer);
?>