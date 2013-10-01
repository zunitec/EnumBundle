<?php

namespace Zuni\EnumBundle\Model;

use ReflectionClass;

/**
 * Gerencia lista de objetos
 */
abstract class AbstractEnumList {

    public static function getInstance() {
        throw new \Exception("Método não implementado! Verifique!");
    }
    
    /**
     *
     * Lista com todos os Atributos publicos da classe filha
     * 
     * @var array 
     */
    private $lista = array();

    /**
     * 
     * Retorna um array com todos os objetos da enum 
     * 
     * @return array
     */
    public function getLista() {
        if (!$this->lista) {
            //Acessando os métodos da classe filha
            $reflection = new ReflectionClass($this);
            /* @var $atributoEnumList ReflectionProperty */
            foreach ($reflection->getProperties() as $atributoEnumList) {
                if ($atributoEnumList->isPublic()) {
                    $model = $atributoEnumList->getValue($this);
                    $this->lista[$model->getId()] = $model;
                }
            }
        }
        return $this->lista;
    }

    /**
     * 
     * Pega um objeto da enum
     * 
     * @param int $id
     * @return AbstractEnumObject
     */
    public function getObject($id) {
        $lista = $this->getLista();
        if (!array_key_exists($id, $lista)) {
            return null;
        }
        return $lista[$id];
    }

}
