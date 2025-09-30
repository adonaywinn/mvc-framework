<?php 
// Carregar autoloader
require_once '../app/core/Autoloader.php';

// Carregar helpers
require_once '../app/helpers/helpers.php';

// Fallback: carregar classes core manualmente se necessário
if (!class_exists('Router')) {
    require_once '../app/core/Router.php';
}
if (!class_exists('Controller')) {
    require_once '../app/core/Controller.php';
}
if (!class_exists('Config')) {
    require_once '../app/core/Config.php';
}

$url = $_GET['url'] ?? '';

$router = new Router();
$router->dispatch($url);



