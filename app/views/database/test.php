<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão - Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background: #f8f9fa; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Teste de Conexão com Banco de Dados</h1>
        
        <?php if ($success): ?>
            <div class="success">
                ✅ <strong>Conexão estabelecida com sucesso!</strong>
            </div>
            
            <div class="info">
                <h3>📊 Informações do Banco</h3>
                <table class="table">
                    <tr><th>Driver</th><td><?= $database_info['driver'] ?></td></tr>
                    <tr><th>Host</th><td><?= $database_info['host'] ?></td></tr>
                    <tr><th>Porta</th><td><?= $database_info['port'] ?></td></tr>
                    <tr><th>Database</th><td><?= $database_info['database'] ?></td></tr>
                    <tr><th>Usuário</th><td><?= $database_info['username'] ?></td></tr>
                    <tr><th>Charset</th><td><?= $database_info['charset'] ?></td></tr>
                </table>
            </div>
            
            <div class="info">
                <h3>🔧 Versão do Banco</h3>
                <p><strong><?= $version ?></strong></p>
            </div>
            
            <div class="info">
                <h3>📋 Tabelas Disponíveis (<?= count($tables) ?> encontradas)</h3>
                <?php if (!empty($tables)): ?>
                    <ul>
                        <?php foreach ($tables as $table): ?>
                            <li><?= $table['table_name'] ?? array_values($table)[0] ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhuma tabela encontrada.</p>
                <?php endif; ?>
            </div>
            
            <div class="info">
                <h3>🧪 Teste de Conexão</h3>
                <p>Status: <?= $connection_test ? '✅ Conectado' : '❌ Desconectado' ?></p>
            </div>
            
        <?php else: ?>
            <div class="error">
                ❌ <strong>Erro na conexão:</strong> <?= $error ?>
            </div>
            
            <div class="info">
                <h3>⚙️ Configurações Tentadas</h3>
                <pre><?= json_encode($database_info, JSON_PRETTY_PRINT) ?></pre>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 20px;">
            <a href="<?= url('database-test/test-query') ?>" class="btn">🧪 Testar Query</a>
            <a href="<?= url('home') ?>" class="btn">🏠 Voltar ao Início</a>
        </div>
    </div>
</body>
</html>
