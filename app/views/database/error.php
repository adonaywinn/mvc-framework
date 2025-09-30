<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Conexão - Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error { color: #dc3545; background: #f8d7da; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .info { background: #d1ecf1; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>❌ Erro de Conexão com Banco de Dados</h1>
        
        <div class="error">
            <h3>🚨 Erro Encontrado:</h3>
            <p><strong><?= $error ?></strong></p>
        </div>
        
        <div class="info">
            <h3>⚙️ Configurações Utilizadas:</h3>
            <pre><?= json_encode($database_info, JSON_PRETTY_PRINT) ?></pre>
        </div>
        
        <div class="info">
            <h3>🔧 Possíveis Soluções:</h3>
            <ul>
                <li>Verifique se o servidor PostgreSQL está rodando</li>
                <li>Confirme se as credenciais estão corretas</li>
                <li>Teste a conectividade de rede</li>
                <li>Verifique se o banco de dados existe</li>
                <li>Confirme se o usuário tem permissões adequadas</li>
            </ul>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="<?= url('database-test') ?>" class="btn">🔄 Tentar Novamente</a>
            <a href="<?= url('home') ?>" class="btn">🏠 Voltar ao Início</a>
        </div>
    </div>
</body>
</html>
