<?php

/**
 * Classe Logger
 * Sistema de logs da aplicação
 */
class Logger {
    
    private static $instance = null;
    private $logPath;
    private $maxFiles;
    private $maxSize;
    private $enabled;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct() {
        $config = Config::logging();
        $this->logPath = $config['path'] ?? __DIR__ . '/../logs/';
        $this->maxFiles = $config['max_files'] ?? 10;
        $this->maxSize = $config['max_size'] ?? 10485760; // 10MB
        $this->enabled = $config['enabled'] ?? true;
        
        $this->ensureLogDirectory();
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
     * Garantir que diretório de logs existe
     */
    private function ensureLogDirectory() {
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }
    
    /**
     * Log de debug
     */
    public static function debug($message, $context = []) {
        self::log('debug', $message, $context);
    }
    
    /**
     * Log de informação
     */
    public static function info($message, $context = []) {
        self::log('info', $message, $context);
    }
    
    /**
     * Log de aviso
     */
    public static function warning($message, $context = []) {
        self::log('warning', $message, $context);
    }
    
    /**
     * Log de erro
     */
    public static function error($message, $context = []) {
        self::log('error', $message, $context);
    }
    
    /**
     * Log de emergência
     */
    public static function emergency($message, $context = []) {
        self::log('emergency', $message, $context);
    }
    
    /**
     * Método principal de log
     */
    public static function log($level, $message, $context = []) {
        $logger = self::getInstance();
        
        if (!$logger->enabled) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        $logFile = $logger->logPath . 'app-' . date('Y-m-d') . '.log';
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Rotacionar logs se necessário
        $logger->rotateLogs();
    }
    
    /**
     * Rotacionar arquivos de log
     */
    private function rotateLogs() {
        $files = glob($this->logPath . 'app-*.log');
        
        if (count($files) > $this->maxFiles) {
            // Ordenar por data de modificação
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Remover arquivos mais antigos
            $filesToRemove = array_slice($files, 0, count($files) - $this->maxFiles);
            foreach ($filesToRemove as $file) {
                unlink($file);
            }
        }
        
        // Verificar tamanho do arquivo atual
        $currentFile = $this->logPath . 'app-' . date('Y-m-d') . '.log';
        if (file_exists($currentFile) && filesize($currentFile) > $this->maxSize) {
            $this->rotateCurrentFile($currentFile);
        }
    }
    
    /**
     * Rotacionar arquivo atual
     */
    private function rotateCurrentFile($file) {
        $timestamp = date('H-i-s');
        $rotatedFile = str_replace('.log', "-{$timestamp}.log", $file);
        rename($file, $rotatedFile);
    }
    
    /**
     * Limpar logs antigos
     */
    public static function clean($days = 30) {
        $logger = self::getInstance();
        $cutoff = time() - ($days * 24 * 60 * 60);
        
        $files = glob($logger->logPath . 'app-*.log');
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }
    
    /**
     * Obter logs de um período
     */
    public static function getLogs($date = null, $level = null) {
        $logger = self::getInstance();
        $date = $date ?: date('Y-m-d');
        $logFile = $logger->logPath . "app-{$date}.log";
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = file($logFile, FILE_IGNORE_NEW_LINES);
        $filteredLogs = [];
        
        foreach ($logs as $log) {
            if ($level && strpos($log, ": {$level}:") === false) {
                continue;
            }
            $filteredLogs[] = $log;
        }
        
        return $filteredLogs;
    }
    
    /**
     * Log de exceção
     */
    public static function exception($exception, $context = []) {
        $message = $exception->getMessage();
        $context['file'] = $exception->getFile();
        $context['line'] = $exception->getLine();
        $context['trace'] = $exception->getTraceAsString();
        
        self::error($message, $context);
    }
    
    /**
     * Log de query SQL
     */
    public static function query($sql, $params = []) {
        $context = ['sql' => $sql, 'params' => $params];
        self::debug('SQL Query executed', $context);
    }
    
    /**
     * Log de performance
     */
    public static function performance($message, $time, $context = []) {
        $context['execution_time'] = $time;
        self::info($message, $context);
    }
}
