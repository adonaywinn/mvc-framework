<?php

/**
 * Classe Response
 * Gerencia respostas HTTP
 */
class Response {
    
    private $statusCode = 200;
    private $headers = [];
    private $content = '';
    private $contentType = 'text/html';
    
    /**
     * Definir código de status
     */
    public function status($code) {
        $this->statusCode = $code;
        return $this;
    }
    
    /**
     * Definir header
     */
    public function header($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }
    
    /**
     * Definir Content-Type
     */
    public function contentType($type) {
        $this->contentType = $type;
        return $this;
    }
    
    /**
     * Definir conteúdo
     */
    public function content($content) {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Resposta JSON
     */
    public function json($data, $status = 200) {
        $this->statusCode = $status;
        $this->contentType = 'application/json';
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this;
    }
    
    /**
     * Resposta de erro JSON
     */
    public function jsonError($message, $status = 400) {
        return $this->json(['error' => $message], $status);
    }
    
    /**
     * Resposta de sucesso JSON
     */
    public function jsonSuccess($data = null, $message = 'Success') {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return $this->json($response);
    }
    
    /**
     * Redirecionar
     */
    public function redirect($url, $status = 302) {
        $this->statusCode = $status;
        $this->header('Location', $url);
        return $this;
    }
    
    /**
     * Resposta de download
     */
    public function download($file, $filename = null) {
        if (!file_exists($file)) {
            return $this->status(404)->content('File not found');
        }
        
        $filename = $filename ?: basename($file);
        $this->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->header('Content-Type', 'application/octet-stream');
        $this->header('Content-Length', filesize($file));
        $this->content = file_get_contents($file);
        return $this;
    }
    
    /**
     * Resposta de arquivo
     */
    public function file($file, $mimeType = null) {
        if (!file_exists($file)) {
            return $this->status(404)->content('File not found');
        }
        
        $mimeType = $mimeType ?: mime_content_type($file);
        $this->contentType($mimeType);
        $this->header('Content-Length', filesize($file));
        $this->content = file_get_contents($file);
        return $this;
    }
    
    /**
     * Resposta de view
     */
    public function view($view, $data = []) {
        // Implementação futura com sistema de views
        $this->content = "View: {$view}";
        return $this;
    }
    
    /**
     * Definir cookie
     */
    public function cookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = false) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return $this;
    }
    
    /**
     * Remover cookie
     */
    public function removeCookie($name, $path = '/', $domain = '') {
        setcookie($name, '', time() - 3600, $path, $domain);
        return $this;
    }
    
    /**
     * Definir cache
     */
    public function cache($seconds) {
        $this->header('Cache-Control', 'public, max-age=' . $seconds);
        return $this;
    }
    
    /**
     * Sem cache
     */
    public function noCache() {
        $this->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->header('Pragma', 'no-cache');
        $this->header('Expires', '0');
        return $this;
    }
    
    /**
     * CORS headers
     */
    public function cors($origin = '*', $methods = 'GET, POST, PUT, DELETE', $headers = 'Content-Type, Authorization') {
        $this->header('Access-Control-Allow-Origin', $origin);
        $this->header('Access-Control-Allow-Methods', $methods);
        $this->header('Access-Control-Allow-Headers', $headers);
        return $this;
    }
    
    /**
     * Enviar resposta
     */
    public function send() {
        // Definir código de status
        http_response_code($this->statusCode);
        
        // Definir Content-Type
        $this->header('Content-Type', $this->contentType);
        
        // Enviar headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        // Enviar conteúdo
        echo $this->content;
        exit;
    }
    
    /**
     * Resposta 200 OK
     */
    public static function ok($content = '') {
        return (new self())->content($content);
    }
    
    /**
     * Resposta 201 Created
     */
    public static function created($content = '') {
        return (new self())->status(201)->content($content);
    }
    
    /**
     * Resposta 400 Bad Request
     */
    public static function badRequest($message = 'Bad Request') {
        return (new self())->status(400)->content($message);
    }
    
    /**
     * Resposta 401 Unauthorized
     */
    public static function unauthorized($message = 'Unauthorized') {
        return (new self())->status(401)->content($message);
    }
    
    /**
     * Resposta 403 Forbidden
     */
    public static function forbidden($message = 'Forbidden') {
        return (new self())->status(403)->content($message);
    }
    
    /**
     * Resposta 404 Not Found
     */
    public static function notFound($message = 'Not Found') {
        return (new self())->status(404)->content($message);
    }
    
    /**
     * Resposta 500 Internal Server Error
     */
    public static function serverError($message = 'Internal Server Error') {
        return (new self())->status(500)->content($message);
    }
}
