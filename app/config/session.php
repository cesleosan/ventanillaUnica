<?php

/**
 * Inicia la sesión exclusiva de VUT.
 *
 * VUT comparte dominio con otros sistemas PHP, por lo que no debe usar la
 * cookie genérica PHPSESSID: otra aplicación podría reemplazarla y provocar
 * que el usuario vuelva al inicio de sesión después de autenticarse.
 */
function vut_iniciar_sesion(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    $protocoloReenviado = strtolower(trim(explode(',', (string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));
    $httpsActivo = (
        (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
        || $protocoloReenviado === 'https'
    );

    session_name('VUT_TLALPAN_SESION');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $httpsActivo,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

/**
 * Cierra únicamente la sesión de VUT y elimina su cookie.
 */
function vut_cerrar_sesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        vut_iniciar_sesion();
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parametros = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $parametros['path'],
            'domain' => $parametros['domain'],
            'secure' => $parametros['secure'],
            'httponly' => $parametros['httponly'],
            'samesite' => $parametros['samesite'] ?? 'Lax'
        ]);
    }

    session_destroy();
}
