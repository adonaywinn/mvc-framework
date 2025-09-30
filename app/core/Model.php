<?php

/**
 * Classe Model Base
 * Fornece funcionalidades comuns para todos os models
 */
abstract class Model {
    
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $attributes = [];
    
    /**
     * Construtor
     */
    public function __construct($attributes = []) {
        $this->attributes = $attributes;
    }
    
    /**
     * Obter todos os registros
     */
    public static function all() {
        // Implementação futura com Database
        return [];
    }
    
    /**
     * Encontrar por ID
     */
    public static function find($id) {
        // Implementação futura com Database
        return null;
    }
    
    /**
     * Criar novo registro
     */
    public static function create($data) {
        // Implementação futura com Database
        return new static($data);
    }
    
    /**
     * Atualizar registro
     */
    public function update($data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    /**
     * Deletar registro
     */
    public function delete() {
        // Implementação futura com Database
        return true;
    }
    
    /**
     * Obter atributo
     */
    public function getAttribute($key) {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Definir atributo
     */
    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Obter todos os atributos
     */
    public function getAttributes() {
        return $this->attributes;
    }
    
    /**
     * Converter para array
     */
    public function toArray() {
        $array = $this->attributes;
        
        // Remover campos hidden
        foreach ($this->hidden as $field) {
            unset($array[$field]);
        }
        
        return $array;
    }
    
    /**
     * Converter para JSON
     */
    public function toJson($options = 0) {
        return json_encode($this->toArray(), $options);
    }
    
    /**
     * Magic method para acessar atributos
     */
    public function __get($key) {
        return $this->getAttribute($key);
    }
    
    /**
     * Magic method para definir atributos
     */
    public function __set($key, $value) {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Magic method para verificar se atributo existe
     */
    public function __isset($key) {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Magic method para converter para string
     */
    public function __toString() {
        return $this->toJson();
    }
}
