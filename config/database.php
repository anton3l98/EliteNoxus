<?php
/**
 * Archivo: config/database.php
 * Configuración de conexión MySQL para EliteNoxus
 */

// Configuración de la base de datos - AJUSTAR SEGÚN TU SERVIDOR
define('DB_HOST', 'localhost');
define('DB_NAME', 'salvad93_elit3signal');
define('DB_USER', 'salvad93_NoxusElite');
define('DB_PASS', '1ca8d8f338.');
define('DB_CHARSET', 'utf8mb4');

/**
 * Clase de conexión a la base de datos usando PDO
 */
class ConexionOmega {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    private $pdo;
    private static $instance = null;

    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConexion() {
        return $this->pdo;
    }

    // Previene la clonación del objeto
    private function __clone() {}

    // Prevente la deserialización del objeto
    public function __wakeup() {
        throw new Exception("No se puede deserializar el singleton de conexión");
    }
}

/**
 * Función helper para obtener la conexión
 */
function getDB() {
    return ConexionOmega::getInstance()->getConexion();
}
