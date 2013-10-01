<?php

namespace Zuni\EnumBundle\Model;

abstract class AbstractEnumObject {

    private $id;
    private $descricao;

    function __construct($id, $descricao) {
        $this->id = $id;
        $this->descricao = $descricao;
    }

    public function __toString() {
        return $this->getDescricao();
    }

    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

}
