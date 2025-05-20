<?php
class Database {
    private $host = "db";
    private $db_name = "php_db";
    private $username = "postgres";
    private $password = "postgres";
    private $port = 5432;

    public function getConnection() {
        $conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $conn = new PDO($dsn, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $conn;
    }
}
?>