<?php

/**
 * Funções Helper do Framework MVC
 * Coleção de funções utilitárias para facilitar o desenvolvimento
 */

if (!function_exists('config')) {
    /**
     * Obter configuração
     */
    function config($key, $default = null) {
        return Config::get($key, $default);
    }
}

if (!function_exists('request')) {
    /**
     * Obter instância da Request
     */
    function request() {
        return Request::getInstance();
    }
}

if (!function_exists('response')) {
    /**
     * Criar nova instância da Response
     */
    function response($content = '', $status = 200) {
        return (new Response())->content($content)->status($status);
    }
}

if (!function_exists('json')) {
    /**
     * Resposta JSON
     */
    function json($data, $status = 200) {
        return (new Response())->json($data, $status);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirecionar
     */
    function redirect($url, $status = 302) {
        return (new Response())->redirect($url, $status);
    }
}

if (!function_exists('view')) {
    /**
     * Carregar view
     */
    function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$viewFile}");
        }
        
        require $viewFile;
    }
}

if (!function_exists('asset')) {
    /**
     * Gerar URL para asset
     */
    function asset($path) {
        $baseUrl = config('app.url', 'http://localhost');
        return rtrim($baseUrl, '/') . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Gerar URL
     */
    function url($path = '') {
        $baseUrl = config('app.url', 'http://localhost');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('route')) {
    /**
     * Gerar URL para rota
     */
    function route($controller, $action = 'index', $params = []) {
        $url = $controller . '/' . $action;
        if (!empty($params)) {
            $url .= '/' . implode('/', $params);
        }
        return url($url);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Gerar token CSRF
     */
    function csrf_token() {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Gerar campo hidden CSRF
     */
    function csrf_field() {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('validate_csrf')) {
    /**
     * Validar token CSRF
     */
    function validate_csrf($token = null) {
        $token = $token ?: request()->input('_token');
        return $token && hash_equals($_SESSION['_token'] ?? '', $token);
    }
}

if (!function_exists('old')) {
    /**
     * Obter valor antigo de input
     */
    function old($key, $default = '') {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    /**
     * Definir mensagem flash
     */
    function flash($key, $message) {
        $_SESSION['_flash'][$key] = $message;
    }
}

if (!function_exists('get_flash')) {
    /**
     * Obter mensagem flash
     */
    function get_flash($key, $default = '') {
        $message = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $message;
    }
}

if (!function_exists('has_flash')) {
    /**
     * Verificar se tem mensagem flash
     */
    function has_flash($key) {
        return isset($_SESSION['_flash'][$key]);
    }
}

if (!function_exists('errors')) {
    /**
     * Obter erros de validação
     */
    function errors($key = null) {
        $errors = $_SESSION['_errors'] ?? [];
        if ($key === null) {
            return $errors;
        }
        return $errors[$key] ?? [];
    }
}

if (!function_exists('has_errors')) {
    /**
     * Verificar se tem erros
     */
    function has_errors($key = null) {
        $errors = errors($key);
        return !empty($errors);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and Die
     */
    function dd(...$vars) {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump sem die
     */
    function dump(...$vars) {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

if (!function_exists('env')) {
    /**
     * Obter variável de ambiente
     */
    function env($key, $default = null) {
        return Config::env($key, $default);
    }
}

if (!function_exists('logger')) {
    /**
     * Logger helper
     */
    function logger($level, $message, $context = []) {
        Logger::log($level, $message, $context);
    }
}

if (!function_exists('log_info')) {
    /**
     * Log de informação
     */
    function log_info($message, $context = []) {
        Logger::info($message, $context);
    }
}

if (!function_exists('log_error')) {
    /**
     * Log de erro
     */
    function log_error($message, $context = []) {
        Logger::error($message, $context);
    }
}

if (!function_exists('log_debug')) {
    /**
     * Log de debug
     */
    function log_debug($message, $context = []) {
        Logger::debug($message, $context);
    }
}

if (!function_exists('sanitize')) {
    /**
     * Sanitizar string
     */
    function sanitize($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML
     */
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('str_random')) {
    /**
     * Gerar string aleatória
     */
    function str_random($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
}

if (!function_exists('hash_password')) {
    /**
     * Hash de senha
     */
    function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password')) {
    /**
     * Verificar senha
     */
    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
}

if (!function_exists('format_date')) {
    /**
     * Formatar data
     */
    function format_date($date, $format = 'd/m/Y H:i:s') {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        return $date->format($format);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Formatar moeda
     */
    function format_currency($value, $currency = 'BRL') {
        return number_format($value, 2, ',', '.') . ' ' . $currency;
    }
}

if (!function_exists('slug')) {
    /**
     * Gerar slug
     */
    function slug($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }
}

if (!function_exists('array_get')) {
    /**
     * Obter valor de array com notação de ponto
     */
    function array_get($array, $key, $default = null) {
        $keys = explode('.', $key);
        $value = $array;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
}

if (!function_exists('array_set')) {
    /**
     * Definir valor em array com notação de ponto
     */
    function array_set(&$array, $key, $value) {
        $keys = explode('.', $key);
        $current = &$array;
        
        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }
        
        $current = $value;
    }
}

if (!function_exists('db')) {
    /**
     * Obter instância da Database
     */
    function db() {
        return Database::getInstance();
    }
}

if (!function_exists('db_query')) {
    /**
     * Executar query no banco
     */
    function db_query($sql, $params = []) {
        return db()->query($sql, $params);
    }
}

if (!function_exists('db_fetch_all')) {
    /**
     * Buscar todos os registros
     */
    function db_fetch_all($sql, $params = []) {
        return db()->fetchAll($sql, $params);
    }
}

if (!function_exists('db_fetch_one')) {
    /**
     * Buscar um registro
     */
    function db_fetch_one($sql, $params = []) {
        return db()->fetchOne($sql, $params);
    }
}

if (!function_exists('db_insert')) {
    /**
     * Inserir registro
     */
    function db_insert($table, $data) {
        return db()->insert($table, $data);
    }
}

if (!function_exists('db_update')) {
    /**
     * Atualizar registro
     */
    function db_update($table, $data, $where, $whereParams = []) {
        return db()->update($table, $data, $where, $whereParams);
    }
}

if (!function_exists('db_delete')) {
    /**
     * Deletar registro
     */
    function db_delete($table, $where, $params = []) {
        return db()->delete($table, $where, $params);
    }
}
