<?php

/**
 * Classe Database
 * Gerencia conexões com banco de dados
 */
class Database {
    
    private static $instance = null;
    private $connection;
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $charset;
    private $driver;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct($config = []) {
        // Carregar configurações do Config se não fornecidas
        if (empty($config)) {
            $config = Config::database();
        }
        
        $this->driver = $config['driver'] ?? 'mysql';
        $this->host = $config['host'] ?? 'localhost';
        $this->port = $config['port'] ?? ($this->driver === 'pgsql' ? '5432' : '3306');
        $this->dbname = $config['dbname'] ?? 'mvc_framework';
        $this->username = $config['username'] ?? 'root';
        $this->password = $config['password'] ?? '';
        $this->charset = $config['charset'] ?? ($this->driver === 'pgsql' ? 'utf8' : 'utf8mb4');
        
        $this->connect();
    }
    
    /**
     * Obter instância única (Singleton)
     */
    public static function getInstance($config = []) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    /**
     * Conectar ao banco de dados
     */
    private function connect() {
        try {
            // Construir DSN baseado no driver
            if ($this->driver === 'pgsql') {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            } else {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
            // Log da conexão bem-sucedida
            Logger::info('Conexão com banco de dados estabelecida', [
                'driver' => $this->driver,
                'host' => $this->host,
                'database' => $this->dbname
            ]);
            
        } catch (PDOException $e) {
            Logger::error('Erro de conexão com banco de dados', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'driver' => $this->driver,
                'host' => $this->host
            ]);
            throw new Exception("Erro de conexão com banco de dados: " . $e->getMessage());
        }
    }
    
    /**
     * Obter conexão PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Executar query preparada
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Erro na query: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar todos os registros
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar um registro
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Inserir registro
     */
    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->connection->lastInsertId();
    }
    
    /**
     * Atualizar registro
     */
    public function update($table, $data, $where, $whereParams = []) {
        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $params = array_merge($data, $whereParams);
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Deletar registro
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Iniciar transação
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Confirmar transação
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Reverter transação
     */
    public function rollback() {
        return $this->connection->rollback();
    }
    
    /**
     * Verificar se está em transação
     */
    public function inTransaction() {
        return $this->connection->inTransaction();
    }
    
    /**
     * Obter último ID inserido
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Contar registros
     */
    public function count($table, $where = '', $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->fetchOne($sql, $params);
        return (int) $result['count'];
    }
    
    /**
     * Verificar se tabela existe
     */
    public function tableExists($table) {
        if ($this->driver === 'pgsql') {
            $sql = "SELECT EXISTS (
                SELECT FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_name = :table
            )";
        } else {
            $sql = "SHOW TABLES LIKE :table";
        }
        
        $result = $this->fetchOne($sql, ['table' => $table]);
        
        if ($this->driver === 'pgsql') {
            return (bool) $result['exists'];
        } else {
            return !empty($result);
        }
    }
    
    /**
     * Obter informações do banco
     */
    public function getDatabaseInfo() {
        return [
            'driver' => $this->driver,
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->dbname,
            'username' => $this->username,
            'charset' => $this->charset
        ];
    }
    
    /**
     * Testar conexão
     */
    public function testConnection() {
        try {
            $this->query("SELECT 1");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
