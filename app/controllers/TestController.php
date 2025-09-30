<?php

class TestController extends Controller {
    
    public function index() {
        echo "Teste básico funcionando!";
    }
    
    public function json() {
        json([
            'success' => true,
            'message' => 'Teste JSON funcionando!',
            'timestamp' => date('Y-m-d H:i:s')
        ])->send();
    }
    
    public function database() {
        try {
            $db = Database::getInstance();
            $result = $db->fetchOne("SELECT current_timestamp as now");
            
            json([
                'success' => true,
                'data' => $result,
                'message' => 'Conexão com banco funcionando!'
            ])->send();
            
        } catch (Exception $e) {
            json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500)->send();
        }
    }
}
