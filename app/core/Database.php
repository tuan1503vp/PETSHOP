<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        );

        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->autoMigrate();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->dbh = null;
        }
    }

    private function autoMigrate() {
        if (!$this->dbh) return;
        try {
            // 1. Kiểm tra và thêm cột 'is_active' vào bảng 'users' nếu chưa có
            $checkUsers = $this->dbh->query("SHOW COLUMNS FROM users LIKE 'is_active'")->fetch();
            if (!$checkUsers) {
                $this->dbh->exec("ALTER TABLE users ADD COLUMN is_active tinyint(1) DEFAULT 1");
            }

            // 2. Kiểm tra và thêm cột 'status' vào bảng 'products' nếu chưa có
            $checkProducts = $this->dbh->query("SHOW COLUMNS FROM products LIKE 'status'")->fetch();
            if (!$checkProducts) {
                $this->dbh->exec("ALTER TABLE products ADD COLUMN status varchar(20) DEFAULT 'active'");
            }
        } catch (Exception $e) {
            error_log("Database Auto-Migration Error: " . $e->getMessage());
        }
    }

    // Prepare statement with query
    public function query($sql) {
        if (is_null($this->dbh)) {
            throw new PDOException("Không thể kết nối cơ sở dữ liệu: " . ($this->error ?? "Lỗi không xác định"));
        }
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Get last insert ID
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
