<?php

namespace Zuni\EnumBundle\Annotation;

/**
 * @Annotation;
 * @Target("CLASS")
 */
class HasEnum {
    /**
     *
     * @var boolean 
     */
    public $has = true;
}