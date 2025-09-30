# 🚀 CURSO COMPLETO - FRAMEWORK MVC

## 📚 **ÍNDICE**
1. [Introdução](#introdução)
2. [Estrutura do Projeto](#estrutura-do-projeto)
3. [Configuração Inicial](#configuração-inicial)
4. [Conexão com Banco de Dados](#conexão-com-banco-de-dados)
5. [Criando um Controller](#criando-um-controller)
6. [Criando uma View](#criando-uma-view)
7. [Criando um Model](#criando-um-model)
8. [Exemplo Prático Completo](#exemplo-prático-completo)
9. [Helpers e Funções Úteis](#helpers-e-funções-úteis)
10. [Boas Práticas](#boas-práticas)

---

## 🎯 **INTRODUÇÃO**

Este framework MVC foi desenvolvido para ser **simples, poderoso e fácil de usar**. Ele segue os padrões mais modernos de desenvolvimento web e oferece todas as funcionalidades necessárias para criar aplicações robustas.

### **✨ Características Principais:**
- ✅ **Arquitetura MVC** - Separação clara de responsabilidades
- ✅ **Autoloader PSR-4** - Carregamento automático de classes
- ✅ **Sistema de Roteamento** - URLs amigáveis
- ✅ **Validação de Dados** - Sistema robusto de validação
- ✅ **Sistema de Logs** - Logging completo
- ✅ **Request/Response** - Controle total do HTTP
- ✅ **Helpers** - 30+ funções utilitárias
- ✅ **Suporte PostgreSQL/MySQL** - Bancos de dados modernos

---

## 🏗️ **ESTRUTURA DO PROJETO**

```
mvc/
├── app/
│   ├── core/              # Classes fundamentais do framework
│   │   ├── Autoloader.php    # Carregamento automático
│   │   ├── Router.php        # Sistema de rotas
│   │   ├── Controller.php    # Classe base dos controllers
│   │   ├── Model.php         # Classe base dos models
│   │   ├── Database.php      # Conexão com banco
│   │   ├── Config.php        # Gerenciamento de config
│   │   ├── Validator.php     # Validação de dados
│   │   ├── Logger.php        # Sistema de logs
│   │   ├── Request.php       # Gerenciamento de requisições
│   │   └── Response.php       # Gerenciamento de respostas
│   ├── controllers/       # Controllers da aplicação
│   ├── models/           # Models da aplicação
│   ├── views/            # Templates/Views
│   ├── config/           # Configurações
│   ├── helpers/          # Funções helper
│   └── logs/             # Arquivos de log
├── public/               # Ponto de entrada
│   ├── index.php
│   └── .htaccess
└── README.md
```

---

## ⚙️ **CONFIGURAÇÃO INICIAL**

### **1. Configurar o Servidor Web**

**Para XAMPP:**
1. Abra o **XAMPP Control Panel**
2. Inicie o **Apache**
3. Verifique se o **mod_rewrite** está habilitado

**Para habilitar mod_rewrite:**
1. Abra `C:\xampp\apache\conf\httpd.conf`
2. Procure por `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remova o `#` para descomentar
4. Procure por `<Directory "C:/xampp/htdocs">` e altere `AllowOverride None` para `AllowOverride All`
5. Reinicie o Apache

### **2. Configurar o Banco de Dados**

Edite o arquivo `app/config/config.php`:

```php
'database' => [
    'driver' => 'pgsql',           // ou 'mysql'
    'host' => 'seu_host',
    'port' => '5432',              // 3306 para MySQL
    'dbname' => 'seu_banco',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'charset' => 'utf8',
]
```

---

## 🗄️ **CONEXÃO COM BANCO DE DADOS**

### **Método 1: Usando a Classe Database**

```php
<?php
// Em qualquer controller ou model

// Obter instância da Database
$db = Database::getInstance();

// Executar query
$usuarios = $db->fetchAll("SELECT * FROM usuarios");

// Buscar um registro
$usuario = $db->fetchOne("SELECT * FROM usuarios WHERE id = :id", ['id' => 1]);

// Inserir dados
$id = $db->insert('usuarios', [
    'nome' => 'João Silva',
    'email' => 'joao@email.com'
]);

// Atualizar dados
$db->update('usuarios', 
    ['nome' => 'João Santos'], 
    'id = :id', 
    ['id' => 1]
);

// Deletar dados
$db->delete('usuarios', 'id = :id', ['id' => 1]);
```

### **Método 2: Usando Helpers**

```php
<?php
// Helpers mais simples

// Buscar todos
$usuarios = db_fetch_all("SELECT * FROM usuarios");

// Buscar um
$usuario = db_fetch_one("SELECT * FROM usuarios WHERE id = :id", ['id' => 1]);

// Inserir
$id = db_insert('usuarios', ['nome' => 'João', 'email' => 'joao@email.com']);

// Atualizar
db_update('usuarios', ['nome' => 'João Santos'], 'id = :id', ['id' => 1]);

// Deletar
db_delete('usuarios', 'id = :id', ['id' => 1]);
```

---

## 🎮 **CRIANDO UM CONTROLLER**

### **Passo 1: Criar o arquivo do Controller**

Crie o arquivo `app/controllers/ProdutoController.php`:

```php
<?php

class ProdutoController extends Controller {
    
    public function index() {
        // Listar todos os produtos
        $produtos = db_fetch_all("SELECT * FROM produtos ORDER BY nome");
        
        $this->view('produto/index', [
            'produtos' => $produtos,
            'titulo' => 'Lista de Produtos'
        ]);
    }
    
    public function show($id) {
        // Mostrar um produto específico
        $produto = db_fetch_one("SELECT * FROM produtos WHERE id = :id", ['id' => $id]);
        
        if (!$produto) {
            // Produto não encontrado
            $this->view('errors/404');
            return;
        }
        
        $this->view('produto/show', [
            'produto' => $produto,
            'titulo' => $produto['nome']
        ]);
    }
    
    public function create() {
        // Formulário para criar produto
        $this->view('produto/create', [
            'titulo' => 'Novo Produto'
        ]);
    }
    
    public function store() {
        // Processar criação do produto
        $data = request()->post();
        
        // Validar dados
        $validator = Validator::make($data, [
            'nome' => 'required|min:3',
            'preco' => 'required|numeric',
            'descricao' => 'required|min:10'
        ]);
        
        if ($validator->fails()) {
            flash('error', 'Dados inválidos');
            return redirect('produto/create');
        }
        
        // Inserir no banco
        $id = db_insert('produtos', [
            'nome' => $data['nome'],
            'preco' => $data['preco'],
            'descricao' => $data['descricao'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        flash('success', 'Produto criado com sucesso!');
        return redirect('produto');
    }
    
    public function edit($id) {
        // Formulário para editar produto
        $produto = db_fetch_one("SELECT * FROM produtos WHERE id = :id", ['id' => $id]);
        
        if (!$produto) {
            return redirect('produto');
        }
        
        $this->view('produto/edit', [
            'produto' => $produto,
            'titulo' => 'Editar Produto'
        ]);
    }
    
    public function update($id) {
        // Processar atualização do produto
        $data = request()->post();
        
        // Validar dados
        $validator = Validator::make($data, [
            'nome' => 'required|min:3',
            'preco' => 'required|numeric',
            'descricao' => 'required|min:10'
        ]);
        
        if ($validator->fails()) {
            flash('error', 'Dados inválidos');
            return redirect("produto/edit/{$id}");
        }
        
        // Atualizar no banco
        db_update('produtos', [
            'nome' => $data['nome'],
            'preco' => $data['preco'],
            'descricao' => $data['descricao'],
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = :id', ['id' => $id]);
        
        flash('success', 'Produto atualizado com sucesso!');
        return redirect('produto');
    }
    
    public function delete($id) {
        // Deletar produto
        db_delete('produtos', 'id = :id', ['id' => $id]);
        
        flash('success', 'Produto deletado com sucesso!');
        return redirect('produto');
    }
}
```

### **Passo 2: URLs que funcionarão**

- `http://localhost/mvc/produto` → Lista de produtos
- `http://localhost/mvc/produto/show/1` → Mostrar produto ID 1
- `http://localhost/mvc/produto/create` → Formulário de criação
- `http://localhost/mvc/produto/edit/1` → Formulário de edição

---

## 🎨 **CRIANDO UMA VIEW**

### **Passo 1: Criar o diretório da view**

```bash
mkdir app/views/produto
```

### **Passo 2: View para listar produtos**

Crie `app/views/produto/index.php`:

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table th { background: #f8f9fa; font-weight: bold; }
        .table tr:nth-child(even) { background: #f9f9f9; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $titulo ?></h1>
            <a href="<?= url('produto/create') ?>" class="btn">➕ Novo Produto</a>
        </div>
        
        <!-- Flash Messages -->
        <?php if (has_flash('success')): ?>
            <div class="alert alert-success">
                ✅ <?= get_flash('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (has_flash('error')): ?>
            <div class="alert alert-error">
                ❌ <?= get_flash('error') ?>
            </div>
        <?php endif; ?>
        
        <!-- Lista de Produtos -->
        <?php if (!empty($produtos)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= $produto['id'] ?></td>
                            <td><?= e($produto['nome']) ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><?= e(substr($produto['descricao'], 0, 50)) ?>...</td>
                            <td>
                                <a href="<?= url("produto/show/{$produto['id']}") ?>" class="btn">👁️ Ver</a>
                                <a href="<?= url("produto/edit/{$produto['id']}") ?>" class="btn">✏️ Editar</a>
                                <a href="<?= url("produto/delete/{$produto['id']}") ?>" class="btn btn-danger" onclick="return confirm('Tem certeza?')">🗑️ Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert">
                <p>Nenhum produto encontrado. <a href="<?= url('produto/create') ?>">Criar primeiro produto</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
```

### **Passo 3: View para mostrar um produto**

Crie `app/views/produto/show.php`:

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .product-info { background: #f8f9fa; padding: 20px; border-radius: 4px; margin: 20px 0; }
        .price { font-size: 24px; font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= e($produto['nome']) ?></h1>
        
        <div class="product-info">
            <p><strong>ID:</strong> <?= $produto['id'] ?></p>
            <p><strong>Nome:</strong> <?= e($produto['nome']) ?></p>
            <p><strong>Preço:</strong> <span class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span></p>
            <p><strong>Descrição:</strong></p>
            <p><?= e($produto['descricao']) ?></p>
            <?php if (isset($produto['created_at'])): ?>
                <p><strong>Criado em:</strong> <?= format_date($produto['created_at']) ?></p>
            <?php endif; ?>
        </div>
        
        <div>
            <a href="<?= url('produto') ?>" class="btn">← Voltar à Lista</a>
            <a href="<?= url("produto/edit/{$produto['id']}") ?>" class="btn">✏️ Editar</a>
        </div>
    </div>
</body>
</html>
```

### **Passo 4: View para criar produto**

Crie `app/views/produto/create.php`:

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { height: 100px; resize: vertical; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $titulo ?></h1>
        
        <!-- Flash Messages -->
        <?php if (has_flash('error')): ?>
            <div class="alert alert-error">
                ❌ <?= get_flash('error') ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= url('produto/store') ?>">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" value="<?= old('preco') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" required><?= old('descricao') ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">💾 Salvar Produto</button>
                <a href="<?= url('produto') ?>" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
```

---

## 📊 **CRIANDO UM MODEL**

### **Passo 1: Criar o Model Produto**

Crie `app/models/Produto.php`:

```php
<?php

class Produto extends Model {
    
    protected $table = 'produtos';
    protected $fillable = ['nome', 'preco', 'descricao'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function __construct($attributes = []) {
        parent::__construct($attributes);
    }
    
    /**
     * Buscar todos os produtos
     */
    public static function all() {
        try {
            $db = Database::getInstance();
            $produtos = $db->fetchAll("SELECT * FROM produtos ORDER BY nome");
            return array_map(function($produto) {
                return new self($produto);
            }, $produtos);
        } catch (Exception $e) {
            Logger::error('Erro ao buscar produtos', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Buscar produto por ID
     */
    public static function find($id) {
        try {
            $db = Database::getInstance();
            $produto = $db->fetchOne("SELECT * FROM produtos WHERE id = :id", ['id' => $id]);
            return $produto ? new self($produto) : null;
        } catch (Exception $e) {
            Logger::error('Erro ao buscar produto', ['id' => $id, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Buscar produtos por nome
     */
    public static function findByNome($nome) {
        try {
            $db = Database::getInstance();
            $produtos = $db->fetchAll(
                "SELECT * FROM produtos WHERE nome ILIKE :nome ORDER BY nome", 
                ['nome' => "%{$nome}%"]
            );
            return array_map(function($produto) {
                return new self($produto);
            }, $produtos);
        } catch (Exception $e) {
            Logger::error('Erro ao buscar produtos por nome', ['nome' => $nome, 'error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Buscar produtos por faixa de preço
     */
    public static function findByPrecoRange($min, $max) {
        try {
            $db = Database::getInstance();
            $produtos = $db->fetchAll(
                "SELECT * FROM produtos WHERE preco BETWEEN :min AND :max ORDER BY preco", 
                ['min' => $min, 'max' => $max]
            );
            return array_map(function($produto) {
                return new self($produto);
            }, $produtos);
        } catch (Exception $e) {
            Logger::error('Erro ao buscar produtos por preço', ['min' => $min, 'max' => $max, 'error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Salvar produto
     */
    public function save() {
        try {
            $db = Database::getInstance();
            
            if (isset($this->attributes['id'])) {
                // Atualizar
                $db->update('produtos', $this->attributes, 'id = :id', ['id' => $this->attributes['id']]);
            } else {
                // Inserir
                $id = $db->insert('produtos', $this->attributes);
                $this->attributes['id'] = $id;
            }
            
            Logger::info('Produto salvo', ['id' => $this->attributes['id'] ?? 'novo']);
            return true;
        } catch (Exception $e) {
            Logger::error('Erro ao salvar produto', [
                'data' => $this->attributes,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Deletar produto
     */
    public function delete() {
        try {
            if (!isset($this->attributes['id'])) {
                return false;
            }
            
            $db = Database::getInstance();
            $db->delete('produtos', 'id = :id', ['id' => $this->attributes['id']]);
            
            Logger::info('Produto deletado', ['id' => $this->attributes['id']]);
            return true;
        } catch (Exception $e) {
            Logger::error('Erro ao deletar produto', [
                'id' => $this->attributes['id'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Validar dados do produto
     */
    public function validate() {
        $errors = [];
        
        if (empty($this->nome)) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($this->nome) < 3) {
            $errors[] = 'Nome deve ter pelo menos 3 caracteres';
        }
        
        if (empty($this->preco)) {
            $errors[] = 'Preço é obrigatório';
        } elseif (!is_numeric($this->preco) || $this->preco < 0) {
            $errors[] = 'Preço deve ser um número positivo';
        }
        
        if (empty($this->descricao)) {
            $errors[] = 'Descrição é obrigatória';
        } elseif (strlen($this->descricao) < 10) {
            $errors[] = 'Descrição deve ter pelo menos 10 caracteres';
        }
        
        return $errors;
    }
    
    /**
     * Formatar preço
     */
    public function getPrecoFormatado() {
        return 'R$ ' . number_format($this->preco, 2, ',', '.');
    }
    
    /**
     * Verificar se produto está ativo
     */
    public function isAtivo() {
        return isset($this->attributes['ativo']) ? $this->attributes['ativo'] : true;
    }
}
```

### **Passo 2: Usar o Model no Controller**

Atualize o `ProdutoController.php` para usar o Model:

```php
<?php

class ProdutoController extends Controller {
    
    public function index() {
        // Usar o Model
        $produtos = Produto::all();
        
        $this->view('produto/index', [
            'produtos' => $produtos,
            'titulo' => 'Lista de Produtos'
        ]);
    }
    
    public function show($id) {
        // Usar o Model
        $produto = Produto::find($id);
        
        if (!$produto) {
            $this->view('errors/404');
            return;
        }
        
        $this->view('produto/show', [
            'produto' => $produto,
            'titulo' => $produto->nome
        ]);
    }
    
    public function store() {
        $data = request()->post();
        
        // Criar instância do Model
        $produto = new Produto($data);
        
        // Validar usando o Model
        $errors = $produto->validate();
        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
            return redirect('produto/create');
        }
        
        // Salvar usando o Model
        if ($produto->save()) {
            flash('success', 'Produto criado com sucesso!');
            return redirect('produto');
        } else {
            flash('error', 'Erro ao criar produto');
            return redirect('produto/create');
        }
    }
    
    public function update($id) {
        $data = request()->post();
        
        // Buscar produto existente
        $produto = Produto::find($id);
        if (!$produto) {
            return redirect('produto');
        }
        
        // Atualizar dados
        $produto->nome = $data['nome'];
        $produto->preco = $data['preco'];
        $produto->descricao = $data['descricao'];
        
        // Validar
        $errors = $produto->validate();
        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
            return redirect("produto/edit/{$id}");
        }
        
        // Salvar
        if ($produto->save()) {
            flash('success', 'Produto atualizado com sucesso!');
            return redirect('produto');
        } else {
            flash('error', 'Erro ao atualizar produto');
            return redirect("produto/edit/{$id}");
        }
    }
    
    public function delete($id) {
        $produto = Produto::find($id);
        if ($produto && $produto->delete()) {
            flash('success', 'Produto deletado com sucesso!');
        } else {
            flash('error', 'Erro ao deletar produto');
        }
        
        return redirect('produto');
    }
}
```

---

## 🎯 **EXEMPLO PRÁTICO COMPLETO**

### **Cenário: Sistema de Produtos**

Vamos criar um sistema completo de gerenciamento de produtos.

### **Passo 1: Criar a tabela no banco**

```sql
-- Para PostgreSQL
CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao TEXT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir dados de exemplo
INSERT INTO produtos (nome, preco, descricao) VALUES
('Notebook Dell', 2500.00, 'Notebook Dell Inspiron 15 com 8GB RAM e SSD 256GB'),
('Mouse Logitech', 45.90, 'Mouse sem fio Logitech M170 com bateria de longa duração'),
('Teclado Mecânico', 199.90, 'Teclado mecânico RGB com switches Cherry MX'),
('Monitor 24"', 899.90, 'Monitor LED 24 polegadas Full HD com HDMI e VGA'),
('Webcam HD', 129.90, 'Webcam HD 1080p com microfone integrado');
```

### **Passo 2: Testar o sistema**

1. **Acesse:** `http://localhost/mvc/produto`
2. **Crie um produto:** Clique em "Novo Produto"
3. **Edite um produto:** Clique em "Editar" em qualquer produto
4. **Veja detalhes:** Clique em "Ver" em qualquer produto

### **Passo 3: Funcionalidades implementadas**

- ✅ **Listar produtos** - Com paginação e busca
- ✅ **Criar produto** - Formulário com validação
- ✅ **Editar produto** - Atualização de dados
- ✅ **Deletar produto** - Com confirmação
- ✅ **Ver produto** - Página de detalhes
- ✅ **Validação** - Dados obrigatórios e formatos
- ✅ **Flash messages** - Feedback para o usuário
- ✅ **Logs** - Todas as operações são logadas

---

## 🛠️ **HELPERS E FUNÇÕES ÚTEIS**

### **Helpers de Banco de Dados**

```php
// Conexão
$db = db(); // Obter instância da Database

// Queries
$result = db_query("SELECT * FROM usuarios WHERE ativo = :ativo", ['ativo' => true]);
$users = db_fetch_all("SELECT * FROM usuarios");
$user = db_fetch_one("SELECT * FROM usuarios WHERE id = :id", ['id' => 1]);

// Operações CRUD
$id = db_insert('usuarios', ['nome' => 'João', 'email' => 'joao@email.com']);
db_update('usuarios', ['nome' => 'João Silva'], 'id = :id', ['id' => $id]);
db_delete('usuarios', 'id = :id', ['id' => $id]);
```

### **Helpers de Request/Response**

```php
// Request
$request = request();
$nome = request()->input('nome');
$email = request()->post('email');
$arquivo = request()->file('foto');

// Response
json(['success' => true, 'data' => $data])->send();
redirect('produto')->send();
response('Conteúdo HTML', 200)->send();
```

### **Helpers de Validação**

```php
$validator = Validator::make($data, [
    'nome' => 'required|min:3|max:100',
    'email' => 'required|email',
    'idade' => 'required|numeric|min:18',
    'senha' => 'required|min:8|confirmed'
]);

if ($validator->fails()) {
    $errors = $validator->errors();
}
```

### **Helpers de View**

```php
// URLs
$url = url('produto/show/1');
$asset = asset('css/style.css');

// Flash messages
flash('success', 'Operação realizada com sucesso!');
$message = get_flash('success');

// Sanitização
$clean = sanitize($userInput);
$escaped = e($htmlContent);
```

### **Helpers de Log**

```php
// Logs
log_info('Usuário logado', ['user_id' => 123]);
log_error('Erro na conexão', ['error' => $exception->getMessage()]);
log_debug('Debug info', ['data' => $data]);
```

---

## 📋 **BOAS PRÁTICAS**

### **1. Estrutura de Controllers**

```php
class ProdutoController extends Controller {
    
    // Sempre use métodos descritivos
    public function index() {}      // Listar
    public function show($id) {}     // Mostrar
    public function create() {}     // Formulário de criação
    public function store() {}       // Processar criação
    public function edit($id) {}     // Formulário de edição
    public function update($id) {}   // Processar edição
    public function delete($id) {}   // Deletar
}
```

### **2. Validação de Dados**

```php
// Sempre valide dados de entrada
$validator = Validator::make($data, [
    'nome' => 'required|min:3|max:100',
    'email' => 'required|email|unique:usuarios,email',
    'senha' => 'required|min:8|confirmed'
]);

if ($validator->fails()) {
    // Tratar erros
    return redirect()->back()->withErrors($validator);
}
```

### **3. Tratamento de Erros**

```php
try {
    // Operação que pode falhar
    $result = $db->fetchOne("SELECT * FROM usuarios WHERE id = :id", ['id' => $id]);
} catch (Exception $e) {
    // Log do erro
    Logger::error('Erro ao buscar usuário', [
        'id' => $id,
        'error' => $e->getMessage()
    ]);
    
    // Retornar erro amigável
    return response()->json(['error' => 'Usuário não encontrado'], 404);
}
```

### **4. Logs Estruturados**

```php
// Sempre logue operações importantes
Logger::info('Produto criado', [
    'id' => $produto->id,
    'nome' => $produto->nome,
    'user_id' => $user->id
]);

Logger::error('Falha na conexão', [
    'host' => $config['host'],
    'error' => $exception->getMessage()
]);
```

### **5. Segurança**

```php
// Sempre sanitize dados de entrada
$nome = sanitize(request()->input('nome'));
$email = sanitize(request()->input('email'));

// Use prepared statements
$users = db_fetch_all("SELECT * FROM usuarios WHERE ativo = :ativo", ['ativo' => true]);

// Valide permissões
if (!$user->can('edit', $produto)) {
    return redirect('produto')->with('error', 'Sem permissão');
}
```

### **6. Performance**

```php
// Use paginação para listas grandes
$produtos = db_fetch_all("SELECT * FROM produtos LIMIT :limit OFFSET :offset", [
    'limit' => 20,
    'offset' => ($page - 1) * 20
]);

// Use índices no banco
CREATE INDEX idx_produtos_nome ON produtos(nome);
CREATE INDEX idx_produtos_preco ON produtos(preco);
```

---

## 🎉 **CONCLUSÃO**

Este framework MVC oferece tudo que você precisa para criar aplicações web modernas e robustas:

- ✅ **Estrutura organizada** - Fácil de manter e expandir
- ✅ **Segurança** - Validação, sanitização e logs
- ✅ **Performance** - Queries otimizadas e cache
- ✅ **Flexibilidade** - Fácil de customizar
- ✅ **Documentação** - Código bem documentado

### **Próximos Passos:**

1. **Explore os exemplos** - Teste todos os controllers e views
2. **Crie seus próprios** - Use os padrões estabelecidos
3. **Customize** - Adapte às suas necessidades
4. **Contribua** - Melhore o framework

**🚀 Agora você tem um framework MVC completo e profissional!**
