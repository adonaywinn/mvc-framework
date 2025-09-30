<?php

class DatabaseTestController extends Controller {
    
    public function index() {
        try {
            // Testar conexão
            $db = Database::getInstance();
            
            // Informações do banco
            $info = $db->getDatabaseInfo();
            
            // Testar query simples
            $result = $db->fetchOne("SELECT version() as version");
            
            // Testar se consegue listar tabelas
            $tables = [];
            if ($info['driver'] === 'pgsql') {
                $tables = $db->fetchAll("
                    SELECT table_name 
                    FROM information_schema.tables 
                    WHERE table_schema = 'public' 
                    ORDER BY table_name
                ");
            } else {
                $tables = $db->fetchAll("SHOW TABLES");
            }
            
            $data = [
                'success' => true,
                'database_info' => $info,
                'version' => $result['version'] ?? 'N/A',
                'tables' => $tables,
                'connection_test' => $db->testConnection()
            ];
            
            $this->view('database/test', $data);
            
        } catch (Exception $e) {
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
                'database_info' => Config::database()
            ];
            
            $this->view('database/error', $data);
        }
    }
    
    public function testQuery() {
        try {
            $db = Database::getInstance();
            
            // Query de teste
            $result = $db->fetchAll("SELECT current_timestamp as now, current_user as user");
            
            // Log da operação
            Logger::info('Teste de query executado', [
                'result' => $result,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            json([
                'success' => true,
                'data' => $result,
                'message' => 'Query executada com sucesso!',
                'timestamp' => date('Y-m-d H:i:s'),
                'database_info' => $db->getDatabaseInfo()
            ])->send();
            
        } catch (Exception $e) {
            // Log do erro
            Logger::error('Erro no teste de query', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 500)->send();
        }
    }
}
