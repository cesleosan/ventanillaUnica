<?php
// public/index.php

require_once '../app/Controllers/DashboardController.php';
require_once '../app/Controllers/VentanillaController.php';

// Router básico
$page = $_GET['route'] ?? 'home';

switch ($page) {
    case 'home':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'ventanilla':
        $controller = new VentanillaController();
        $controller->index();
        break;
        
    default:
        // Podrías crear una vista 404 bonita después
        header("HTTP/1.0 404 Not Found");
        echo "404 - Página no encontrada";
        break;
}