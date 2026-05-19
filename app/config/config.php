<?php
// Configuración de acceso a la Base de Datos (LOCAL)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Usuario local usual
define('DB_PASS', 'root');          // Contraseña local (MAMP suele usar 'root', XAMPP vacía '')
define('DB_NAME', 'censo_tlalpan'); // Asegúrate que coincida con el nombre en tu phpMyAdmin

// Definición de Rutas de Carpetas
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost:8000');
define('SITENAME', 'Ventanilla Única - Tlalpan');