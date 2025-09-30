# 🤝 Guia de Contribuição

Obrigado por considerar contribuir com o MVC Framework! Este documento fornece diretrizes para contribuir com o projeto.

## 🚀 Como Contribuir

### 1. Fork o Projeto

1. Acesse o [repositório principal](https://github.com/seu-usuario/mvc-framework)
2. Clique no botão "Fork" no canto superior direito
3. Clone seu fork localmente:

```bash
git clone https://github.com/SEU-USUARIO/mvc-framework.git
cd mvc-framework
```

### 2. Configure o Ambiente

```bash
# Instale as dependências (se houver)
composer install

# Configure o banco de dados
cp app/config/config.php.example app/config/config.php
# Edite as configurações do banco
```

### 3. Crie uma Branch

```bash
git checkout -b feature/nova-funcionalidade
# ou
git checkout -b fix/corrigir-bug
```

### 4. Faça suas Alterações

- Siga os padrões de código existentes
- Adicione testes se necessário
- Documente novas funcionalidades
- Mantenha a compatibilidade com versões anteriores

### 5. Commit suas Alterações

```bash
git add .
git commit -m "feat: adiciona nova funcionalidade X"
```

**Formato de commits:**
- `feat:` nova funcionalidade
- `fix:` correção de bug
- `docs:` documentação
- `style:` formatação
- `refactor:` refatoração
- `test:` testes

### 6. Push para seu Fork

```bash
git push origin feature/nova-funcionalidade
```

### 7. Abra um Pull Request

1. Vá para o repositório original no GitHub
2. Clique em "New Pull Request"
3. Selecione sua branch
4. Descreva suas alterações
5. Aguarde a revisão

## 📋 Padrões de Código

### PHP

```php
<?php

/**
 * Descrição da classe
 */
class MinhaClasse {
    
    private $propriedade;
    
    /**
     * Construtor
     */
    public function __construct($parametro) {
        $this->propriedade = $parametro;
    }
    
    /**
     * Método público
     */
    public function metodoPublico() {
        // Implementação
    }
    
    /**
     * Método privado
     */
    private function metodoPrivado() {
        // Implementação
    }
}
```

### Convenções

- **Nomes de classes:** PascalCase (`MinhaClasse`)
- **Nomes de métodos:** camelCase (`meuMetodo`)
- **Nomes de variáveis:** snake_case (`minha_variavel`)
- **Constantes:** UPPER_CASE (`MINHA_CONSTANTE`)
- **Indentação:** 4 espaços
- **Chaves:** na mesma linha da declaração

## 🧪 Testes

### Executar Testes

```bash
# Testes unitários
php tests/Unit/TestExample.php

# Testes de integração
php tests/Integration/TestDatabase.php
```

### Escrever Testes

```php
<?php

class TestMinhaClasse extends PHPUnit\Framework\TestCase {
    
    public function testMetodoRetornaValorCorreto() {
        $classe = new MinhaClasse('teste');
        $resultado = $classe->meuMetodo();
        
        $this->assertEquals('valor esperado', $resultado);
    }
}
```

## 📝 Documentação

### Adicionar Documentação

1. **README.md** - Atualize se necessário
2. **CURSO_FRAMEWORK_MVC.md** - Adicione novos exemplos
3. **Comentários no código** - Documente funções complexas
4. **Exemplos** - Crie exemplos práticos

### Formato de Documentação

```php
/**
 * Descrição do método
 * 
 * @param string $parametro1 Descrição do parâmetro
 * @param int $parametro2 Descrição do parâmetro
 * @return array Descrição do retorno
 * @throws Exception Quando algo dá errado
 * 
 * @example
 * $resultado = $classe->meuMetodo('valor1', 123);
 */
public function meuMetodo($parametro1, $parametro2) {
    // Implementação
}
```

## 🐛 Reportar Bugs

### Antes de Reportar

1. Verifique se o bug já foi reportado
2. Teste na versão mais recente
3. Verifique os logs em `app/logs/`

### Como Reportar

Use o template de issue:

```markdown
**Descrição do Bug**
Descrição clara do problema.

**Passos para Reproduzir**
1. Vá para '...'
2. Clique em '...'
3. Veja o erro

**Comportamento Esperado**
O que deveria acontecer.

**Screenshots**
Se aplicável.

**Informações do Sistema**
- PHP: 7.4.x
- Framework: v1.0.0
- OS: Windows 10

**Logs**
```
[2024-01-01 10:00:00] error: Mensagem de erro
```
```

## ✨ Sugerir Funcionalidades

### Antes de Sugerir

1. Verifique se a funcionalidade já existe
2. Considere se é realmente necessária
3. Pense na implementação

### Como Sugerir

Use o template de feature request:

```markdown
**Funcionalidade**
Descrição clara da funcionalidade.

**Problema que Resolve**
Por que essa funcionalidade é necessária.

**Solução Proposta**
Como você imagina que deveria funcionar.

**Alternativas Consideradas**
Outras soluções que você considerou.

**Contexto Adicional**
Qualquer informação adicional.
```

## 🏷️ Versionamento

Seguimos [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.0.0) - Mudanças incompatíveis
- **MINOR** (0.1.0) - Novas funcionalidades compatíveis
- **PATCH** (0.0.1) - Correções de bugs

## 📞 Suporte

- 📧 **Email:** seu-email@exemplo.com
- 💬 **Discord:** [Link do servidor]
- 📖 **Wiki:** [GitHub Wiki](https://github.com/seu-usuario/mvc-framework/wiki)

## 🙏 Reconhecimentos

- Obrigado a todos os contribuidores
- Comunidade PHP
- Desenvolvedores que testaram e reportaram bugs

---

**Juntos construímos um framework melhor! 🚀**
