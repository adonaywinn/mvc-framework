<?php

/**
 * Classe Config
 * Gerencia configurações da aplicação
 */
class Config {
    
    private static $config = [];
    private static $loaded = false;
    
    /**
     * Carregar configurações
     */
    public static function load() {
        if (!self::$loaded) {
            $configFile = __DIR__ . '/../config/config.php';
            if (file_exists($configFile)) {
                self::$config = require $configFile;
                self::$loaded = true;
            }
        }
    }
    
    /**
     * Obter configuração
     */
    public static function get($key, $default = null) {
        self::load();
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    /**
     * Definir configuração
     */
    public static function set($key, $value) {
        self::load();
        
        $keys = explode('.', $key);
        $config = &self::$config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
    }
    
    /**
     * Verificar se configuração existe
     */
    public static function has($key) {
        self::load();
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return false;
            }
            $value = $value[$k];
        }
        
        return true;
    }
    
    /**
     * Obter todas as configurações
     */
    public static function all() {
        self::load();
        return self::$config;
    }
    
    /**
     * Obter configuração de ambiente
     */
    public static function env($key, $default = null) {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
    
    /**
     * Verificar se está em modo debug
     */
    public static function isDebug() {
        return self::get('app.debug', false);
    }
    
    /**
     * Obter configuração de banco de dados
     */
    public static function database() {
        return self::get('database', []);
    }
    
    /**
     * Obter configuração de segurança
     */
    public static function security() {
        return self::get('security', []);
    }
    
    /**
     * Obter configuração de logs
     */
    public static function logging() {
        return self::get('logging', []);
    }
}
