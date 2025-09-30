<?php

class Router {

    public function dispatch($url) {
        // Log da requisição
        Logger::info('Nova requisição', [
            'url' => $url,
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        $url = trim($url, '/');
        $parts = $url ? explode('/', $url) : [];

        $controllerName = $parts[0] ?? 'home';
        $controllerName = ucfirst($controllerName) . 'Controller';

        $actionName = $parts[1] ?? 'index';

        // Log do roteamento
        Logger::debug('Roteamento', [
            'controller' => $controllerName,
            'action' => $actionName,
            'params' => array_slice($parts, 2)
        ]);

        if(!class_exists($controllerName)) {
            Logger::warning('Controller não encontrado', ['controller' => $controllerName]);
            $controllerName = 'HttpErrorController';
            $controller = new $controllerName();
            $controller->notFound();
            return;
        }

        $controller = new $controllerName();

        if(!method_exists($controller, $actionName)) {
            Logger::warning('Action não encontrada', [
                'controller' => $controllerName,
                'action' => $actionName
            ]);
            $controllerName = 'HttpErrorController';
            $controller = new $controllerName();
            $controller->notFound();
            return;
        }

        $params = array_slice($parts, 2);

        try {
            call_user_func_array([$controller, $actionName], $params);
            Logger::info('Requisição processada com sucesso', [
                'controller' => $controllerName,
                'action' => $actionName
            ]);
        } catch (Exception $e) {
            Logger::error('Erro ao processar requisição', [
                'controller' => $controllerName,
                'action' => $actionName,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

}