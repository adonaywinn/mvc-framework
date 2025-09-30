# 🚀 MVC Framework

Um framework PHP MVC moderno, simples e poderoso para desenvolvimento web.

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Framework](https://img.shields.io/badge/Framework-MVC-orange.svg)](https://github.com)

## ✨ Características

- ✅ **Arquitetura MVC** - Separação clara de responsabilidades
- ✅ **Autoloader PSR-4** - Carregamento automático de classes
- ✅ **Sistema de Roteamento** - URLs amigáveis e flexíveis
- ✅ **Validação de Dados** - Sistema robusto de validação
- ✅ **Sistema de Logs** - Logging completo da aplicação
- ✅ **Request/Response** - Controle total do HTTP
- ✅ **Helpers** - 30+ funções utilitárias
- ✅ **Configuração** - Sistema centralizado de config
- ✅ **Segurança** - Proteção contra ataques comuns
- ✅ **Suporte PostgreSQL/MySQL** - Bancos de dados modernos

## 🏗️ Estrutura do Projeto

```
mvc/
├── app/
│   ├── core/              # Classes fundamentais
│   ├── controllers/        # Controllers
│   ├── models/           # Models
│   ├── views/            # Templates
│   ├── config/           # Configurações
│   ├── helpers/          # Funções helper
│   └── logs/             # Logs
├── public/               # Ponto de entrada
└── docs/                 # Documentação
```

## 🚀 Instalação

### Pré-requisitos

- PHP 7.4 ou superior
- Apache com mod_rewrite habilitado
- PostgreSQL ou MySQL

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/mvc-framework.git
cd mvc-framework
```

### 2. Configure o servidor web

**Para XAMPP:**
1. Copie o projeto para `htdocs/mvc`
2. Habilite mod_rewrite no Apache
3. Configure `AllowOverride All` no httpd.conf

### 3. Configure o banco de dados

Edite `app/config/config.php`:

```php
'database' => [
    'driver' => 'pgsql',        // ou 'mysql'
    'host' => 'localhost',
    'port' => '5432',           // 3306 para MySQL
    'dbname' => 'seu_banco',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
]
```

### 4. Teste a instalação

Acesse: `http://localhost/mvc/database-test`

## 📖 Documentação

- 📚 [Curso Completo](CURSO_FRAMEWORK_MVC.md) - Tutorial passo a passo
- 🎯 [Exemplos Práticos](docs/examples.md) - Códigos de exemplo
- 🔧 [API Reference](docs/api.md) - Documentação da API
- 🛠️ [Guia de Contribuição](CONTRIBUTING.md) - Como contribuir

## 🎯 Exemplo Rápido

### Controller

```php
<?php
class ProdutoController extends Controller {
    
    public function index() {
        $produtos = db_fetch_all("SELECT * FROM produtos");
        $this->view('produto/index', ['produtos' => $produtos]);
    }
    
    public function store() {
        $data = request()->post();
        
        $validator = Validator::make($data, [
            'nome' => 'required|min:3',
            'preco' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            flash('error', 'Dados inválidos');
            return redirect('produto/create');
        }
        
        db_insert('produtos', $data);
        flash('success', 'Produto criado!');
        return redirect('produto');
    }
}
```

### View

```php
<!DOCTYPE html>
<html>
<head>
    <title>Produtos</title>
</head>
<body>
    <h1>Lista de Produtos</h1>
    
    <?php if (has_flash('success')): ?>
        <div class="alert"><?= get_flash('success') ?></div>
    <?php endif; ?>
    
    <table>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= e($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
```

## 🛠️ Funcionalidades

### Sistema de Roteamento
- URLs amigáveis: `/produto/show/1`
- Roteamento automático baseado em convenções
- Suporte a parâmetros dinâmicos

### Validação de Dados
```php
$validator = Validator::make($data, [
    'nome' => 'required|min:3|max:100',
    'email' => 'required|email|unique:usuarios',
    'senha' => 'required|min:8|confirmed'
]);
```

### Helpers Úteis
```php
// URLs
$url = url('produto/show/1');
$asset = asset('css/style.css');

// Banco de dados
$users = db_fetch_all("SELECT * FROM usuarios");
$user = db_fetch_one("SELECT * FROM usuarios WHERE id = :id", ['id' => 1]);

// Flash messages
flash('success', 'Operação realizada!');
$message = get_flash('success');

// Logs
log_info('Usuário logado', ['user_id' => 123]);
log_error('Erro na conexão', ['error' => $exception->getMessage()]);
```

## 🔒 Segurança

- ✅ **Validação de entrada** - Todos os dados são validados
- ✅ **Sanitização** - Dados são sanitizados automaticamente
- ✅ **Prepared Statements** - Proteção contra SQL Injection
- ✅ **CSRF Protection** - Tokens de segurança
- ✅ **Logs de segurança** - Todas as operações são logadas

## 📊 Performance

- ✅ **Autoloader otimizado** - Carregamento eficiente de classes
- ✅ **Queries otimizadas** - Prepared statements
- ✅ **Sistema de cache** - Cache de configurações
- ✅ **Logs estruturados** - Logs eficientes

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👥 Autores

- **Adonay Nascimento** - *Desenvolvimento inicial* - [GitHub](https://github.com/adonaywinn)

## 🙏 Agradecimentos

- Comunidade PHP
- Desenvolvedores que contribuíram
- Todos que testaram e reportaram bugs

## 📞 Suporte

- 📧 Email: seu-email@exemplo.com
- 🐛 Issues: [GitHub Issues](https://github.com/adonaywinn/mvc-framework/issues)
- 📖 Wiki: [GitHub Wiki](https://github.com/adonaywinn/mvc-framework/wiki)

---


**Desenvolvido com ❤️ usando PHP puro**
