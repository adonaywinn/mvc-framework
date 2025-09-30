<?php

/**
 * Classe Validator
 * Sistema de validação de dados
 */
class Validator {
    
    private $data;
    private $rules;
    private $errors = [];
    private $messages = [];
    
    /**
     * Construtor
     */
    public function __construct($data, $rules, $messages = []) {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
    }
    
    /**
     * Criar nova instância de validação
     */
    public static function make($data, $rules, $messages = []) {
        return new self($data, $rules, $messages);
    }
    
    /**
     * Executar validação
     */
    public function validate() {
        foreach ($this->rules as $field => $rule) {
            $this->validateField($field, $rule);
        }
        
        return empty($this->errors);
    }
    
    /**
     * Validar campo específico
     */
    private function validateField($field, $rules) {
        $rules = explode('|', $rules);
        
        foreach ($rules as $rule) {
            $this->applyRule($field, $rule);
        }
    }
    
    /**
     * Aplicar regra de validação
     */
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleValue = $ruleParts[1] ?? null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, 'required', 'O campo :field é obrigatório');
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email', 'O campo :field deve ser um email válido');
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->addError($field, 'min', "O campo :field deve ter pelo menos {$ruleValue} caracteres");
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->addError($field, 'max', "O campo :field deve ter no máximo {$ruleValue} caracteres");
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'numeric', 'O campo :field deve ser numérico');
                }
                break;
                
            case 'integer':
                if (!empty($value) && !is_int($value) && !ctype_digit($value)) {
                    $this->addError($field, 'integer', 'O campo :field deve ser um número inteiro');
                }
                break;
                
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, 'url', 'O campo :field deve ser uma URL válida');
                }
                break;
                
            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, 'date', 'O campo :field deve ser uma data válida');
                }
                break;
                
            case 'in':
                $allowedValues = explode(',', $ruleValue);
                if (!empty($value) && !in_array($value, $allowedValues)) {
                    $this->addError($field, 'in', "O campo :field deve ser um dos valores: {$ruleValue}");
                }
                break;
                
            case 'regex':
                if (!empty($value) && !preg_match($ruleValue, $value)) {
                    $this->addError($field, 'regex', 'O campo :field não atende ao formato esperado');
                }
                break;
                
            case 'unique':
                // Implementação futura com Database
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if (!isset($this->data[$confirmField]) || $value !== $this->data[$confirmField]) {
                    $this->addError($field, 'confirmed', 'O campo :field não confere com a confirmação');
                }
                break;
        }
    }
    
    /**
     * Adicionar erro
     */
    private function addError($field, $rule, $message) {
        if (isset($this->messages["{$field}.{$rule}"])) {
            $message = $this->messages["{$field}.{$rule}"];
        }
        
        $message = str_replace(':field', $field, $message);
        
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $this->errors[$field][] = $message;
    }
    
    /**
     * Obter erros
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * Obter primeiro erro de um campo
     */
    public function first($field) {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Verificar se há erros
     */
    public function fails() {
        return !empty($this->errors);
    }
    
    /**
     * Verificar se não há erros
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Obter todos os erros como string
     */
    public function getErrorsAsString($separator = '<br>') {
        $allErrors = [];
        foreach ($this->errors as $field => $errors) {
            $allErrors = array_merge($allErrors, $errors);
        }
        return implode($separator, $allErrors);
    }
}
