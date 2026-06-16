<?php

class UsuariosController {
    private $db;

    public function __construct() {
        require_once '../app/config/config.php';
        require_once '../app/Libraries/Database.php';
        require_once '../app/Models/Usuario.php';

        $database = new \Database();
        $this->db = $database->getConnection();
    }

    private function puedeAdministrar(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rol = strtolower((string)(
            $_SESSION['user']['rol']
            ?? $_SESSION['user']['role']
            ?? $_SESSION['rol']
            ?? ''
        ));

        $modulo = strtoupper((string)(
            $_SESSION['user']['modulo']
            ?? $_SESSION['modulo']
            ?? ''
        ));

        return in_array($rol, ['root', 'supervisor'], true) && $modulo === 'VUT';
    }

    private function json(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function index(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->puedeAdministrar()) {
            http_response_code(403);
            die('No tienes permisos para administrar usuarios VUT. Inicia sesión con un usuario VUT root o supervisor.');
        }

        $modelo = new \Usuario($this->db);
        $q = trim((string)($_GET['q'] ?? ''));

        $data = [
            'pageTitle' => 'Administración de Usuarios VUT',
            'user' => $_SESSION['user'] ?? null,
            'usuarios' => $modelo->listar($q),
            'roles' => $modelo->rolesPermitidos(),
            'q' => $q,
            'modulo' => 'VUT'
        ];

        $viewContent = '../app/Views/usuarios/index.php';
        require_once '../app/Views/layouts/main.php';
    }

    public function guardar(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->puedeAdministrar()) {
            $this->json(['success' => false, 'error' => 'No tienes permisos para administrar usuarios VUT.'], 403);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        $modelo = new \Usuario($this->db);
        $resultado = $modelo->guardar($payload);

        $this->json($resultado, empty($resultado['success']) ? 400 : 200);
    }

    public function toggle(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->puedeAdministrar()) {
            $this->json(['success' => false, 'error' => 'No tienes permisos para administrar usuarios VUT.'], 403);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        $id = (int)($payload['id'] ?? 0);
        $activo = (int)($payload['activo'] ?? 0);

        $modelo = new \Usuario($this->db);
        $resultado = $modelo->cambiarActivo($id, $activo);

        $this->json($resultado, empty($resultado['success']) ? 400 : 200);
    }
}
