<?php

/**
 * Classe Request
 * Gerencia dados da requisição HTTP
 */
class Request {
    
    private static $instance = null;
    private $get;
    private $post;
    private $files;
    private $server;
    private $headers;
    private $method;
    private $uri;
    private $ip;
    private $userAgent;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->ip = $this->getClientIp();
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->headers = $this->getAllHeaders();
    }
    
    /**
     * Obter instância única (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obter parâmetro GET
     */
    public function get($key = null, $default = null) {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }
    
    /**
     * Obter parâmetro POST
     */
    public function post($key = null, $default = null) {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }
    
    /**
     * Obter parâmetro de qualquer método
     */
    public function input($key = null, $default = null) {
        $data = array_merge($this->get, $this->post);
        if ($key === null) {
            return $data;
        }
        return $data[$key] ?? $default;
    }
    
    /**
     * Obter arquivo enviado
     */
    public function file($key) {
        return $this->files[$key] ?? null;
    }
    
    /**
     * Obter todos os arquivos
     */
    public function files() {
        return $this->files;
    }
    
    /**
     * Obter header
     */
    public function header($key) {
        $key = strtolower($key);
        return $this->headers[$key] ?? null;
    }
    
    /**
     * Obter todos os headers
     */
    public function headers() {
        return $this->headers;
    }
    
    /**
     * Obter método HTTP
     */
    public function method() {
        return $this->method;
    }
    
    /**
     * Verificar se é GET
     */
    public function isGet() {
        return $this->method === 'GET';
    }
    
    /**
     * Verificar se é POST
     */
    public function isPost() {
        return $this->method === 'POST';
    }
    
    /**
     * Verificar se é PUT
     */
    public function isPut() {
        return $this->method === 'PUT';
    }
    
    /**
     * Verificar se é DELETE
     */
    public function isDelete() {
        return $this->method === 'DELETE';
    }
    
    /**
     * Verificar se é AJAX
     */
    public function isAjax() {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }
    
    /**
     * Verificar se é JSON
     */
    public function isJson() {
        return strpos($this->header('Content-Type'), 'application/json') !== false;
    }
    
    /**
     * Obter URI
     */
    public function uri() {
        return $this->uri;
    }
    
    /**
     * Obter URL completa
     */
    public function url() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $this->uri;
    }
    
    /**
     * Obter IP do cliente
     */
    public function ip() {
        return $this->ip;
    }
    
    /**
     * Obter User Agent
     */
    public function userAgent() {
        return $this->userAgent;
    }
    
    /**
     * Obter IP real do cliente
     */
    private function getClientIp() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Obter todos os headers
     */
    private function getAllHeaders() {
        if (function_exists('getallheaders')) {
            return array_change_key_case(getallheaders(), CASE_LOWER);
        }
        
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[strtolower($header)] = $value;
            }
        }
        
        return $headers;
    }
    
    /**
     * Obter dados JSON
     */
    public function json() {
        if ($this->isJson()) {
            $input = file_get_contents('php://input');
            return json_decode($input, true);
        }
        return null;
    }
    
    /**
     * Verificar se tem parâmetro
     */
    public function has($key) {
        return isset($this->get[$key]) || isset($this->post[$key]);
    }
    
    /**
     * Obter apenas parâmetros preenchidos
     */
    public function only($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $data = $this->input();
        return array_intersect_key($data, array_flip($keys));
    }
    
    /**
     * Obter parâmetros exceto os especificados
     */
    public function except($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $data = $this->input();
        return array_diff_key($data, array_flip($keys));
    }
    
    /**
     * Sanitizar dados
     */
    public function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
