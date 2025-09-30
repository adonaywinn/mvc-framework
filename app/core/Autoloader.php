<?php

/**
 * Autoloader PSR-4 Compatible
 * Carrega automaticamente todas as classes do framework
 */
class Autoloader {
    
    private static $namespaces = [];
    private static $directories = [];
    
    /**
     * Registrar namespace para autoload
     */
    public static function registerNamespace($namespace, $directory) {
        self::$namespaces[$namespace] = rtrim($directory, '/') . '/';
    }
    
    /**
     * Registrar diretório para autoload
     */
    public static function registerDirectory($directory) {
        self::$directories[] = rtrim($directory, '/') . '/';
    }
    
    /**
     * Carregar classe automaticamente
     */
    public static function load($className) {
        // Tentar carregar por namespace primeiro
        foreach (self::$namespaces as $namespace => $directory) {
            if (strpos($className, $namespace) === 0) {
                $relativePath = substr($className, strlen($namespace));
                $file = $directory . str_replace('\\', '/', $relativePath) . '.php';
                
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
        }
        
        // Tentar carregar por diretórios registrados
        foreach (self::$directories as $directory) {
            $file = $directory . $className . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        
        // Debug: mostrar tentativas
        if (Config::get('app.debug', false)) {
            error_log("Autoloader: Tentando carregar classe '{$className}'");
            error_log("Autoloader: Namespaces registrados: " . print_r(self::$namespaces, true));
            error_log("Autoloader: Diretórios registrados: " . print_r(self::$directories, true));
        }
        
        return false;
    }
    
    /**
     * Inicializar autoloader
     */
    public static function init() {
        // Registrar namespaces do framework
        self::registerNamespace('App\\Core\\', __DIR__ . '/');
        self::registerNamespace('App\\Controllers\\', __DIR__ . '/../controllers/');
        self::registerNamespace('App\\Models\\', __DIR__ . '/../models/');
        
        // Registrar diretórios adicionais
        self::registerDirectory(__DIR__ . '/'); // Core directory
        self::registerDirectory(__DIR__ . '/../controllers/');
        self::registerDirectory(__DIR__ . '/../models/');
        
        // Registrar função de autoload
        spl_autoload_register([self::class, 'load']);
    }
}

// Inicializar autoloader automaticamente
Autoloader::init();
