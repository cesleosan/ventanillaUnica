<?php

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $charset;

    private $dbh;
    private $error;

    public function __construct() {
        $this->host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $this->user = defined('DB_USER') ? DB_USER : 'root';
        $this->pass = defined('DB_PASS') ? DB_PASS : '';
        $this->dbname = defined('DB_NAME') ? DB_NAME : '';
        $this->charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset} COLLATE {$this->charset}_unicode_ci"
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();

            error_log("ERROR DB CONNECTION: " . $this->error);

            if (defined('APP_ENV') && APP_ENV === 'local') {
                die("Error de conexión: " . htmlspecialchars($this->error, ENT_QUOTES, 'UTF-8'));
            }

            die("Error de conexión a la base de datos.");
        }
    }

    public function getConnection() {
        return $this->dbh;
    }
}