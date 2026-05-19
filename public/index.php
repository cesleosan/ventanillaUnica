<?php
// public/index.php

session_start();

require_once '../app/Controllers/DashboardController.php';
require_once '../app/Controllers/VentanillaController.php';

$page = $_GET['route'] ?? 'home';

try {
    switch ($page) {
        case 'home':
            $controller = new DashboardController();
            $controller->index();
            break;

        case 'ventanilla':
        case 'ventanilla/dashboard':
            $controller = new VentanillaController();
            $controller->dashboard();
            break;

        case 'ventanilla/nueva':
        case 'ventanilla/captura':
            $controller = new VentanillaController();
            $controller->index();
            break;

        case 'ventanilla/editar':
        case 'ventanilla/edit':
            $controller = new VentanillaController();
            $controller->editar();
            break;

        case 'guardar_tramite':
            $controller = new VentanillaController();
            $controller->guardar();
            break;

        case 'ventanilla/actualizar':
            $controller = new VentanillaController();
            $controller->actualizar();
            break;

        case 'ventanilla/generarComprobante':
            $controller = new VentanillaController();
            $controller->generarComprobante();
            break;

        case 'ventanilla/dashboardData':
            $controller = new VentanillaController();
            $controller->dashboardData();
            break;

        case 'ventanilla/cambiarEstado':
            $controller = new VentanillaController();
            $controller->cambiarEstado();
            break;

        case 'ventanilla/detalle':
            $controller = new VentanillaController();
            $controller->detalle();
            break;

        default:
            header('HTTP/1.0 404 Not Found');
            echo '404 - Página no encontrada';
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Error interno</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
