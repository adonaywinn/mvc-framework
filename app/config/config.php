<?php

/**
 * Configurações do Framework MVC
 */

return [
    // Configurações da aplicação
    'app' => [
        'name' => 'MVC Framework',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'America/Sao_Paulo',
        'url' => 'http://localhost/mvc',
    ],
    
    // Configurações do banco de dados
    'database' => [
        'driver' => 'mysql', // MySQL
        'host' => 'localhost',
        'port' => '3306',
        'dbname' => 'mvc_framework',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    
    // Configurações de segurança
    'security' => [
        'csrf_token_name' => '_token',
        'session_name' => 'MVC_SESSION',
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutos
    ],
    
    // Configurações de logs
    'logging' => [
        'enabled' => true,
        'level' => 'debug', // debug, info, warning, error
        'path' => __DIR__ . '/../logs/',
        'max_files' => 10,
        'max_size' => 10485760, // 10MB
    ],
    
    // Configurações de cache
    'cache' => [
        'enabled' => false,
        'driver' => 'file', // file, redis, memcached
        'path' => __DIR__ . '/../cache/',
        'ttl' => 3600, // 1 hora
    ],
    
    // Configurações de email
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from' => [
            'email' => 'noreply@example.com',
            'name' => 'MVC Framework'
        ]
    ],
    
    // Configurações de upload
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'path' => __DIR__ . '/../storage/uploads/',
    ],
    
    // Configurações de paginação
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],
    
    // Configurações de rotas
    'routes' => [
        'default_controller' => 'home',
        'default_action' => 'index',
        'case_sensitive' => false,
    ]
];
