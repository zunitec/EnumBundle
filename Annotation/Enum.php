<?php

namespace Zuni\EnumBundle\Annotation;

/**
 * @Annotation;
 * @Target("PROPERTY")
 */
class Enum {
    /**
     *
     * @var string 
     */
    public $enumList; 
}