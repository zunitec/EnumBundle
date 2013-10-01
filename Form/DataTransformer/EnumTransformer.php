<?php

namespace Zuni\EnumBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EnumTransformer implements DataTransformerInterface
{

    /**
     *
     * @var \Zuni\EnumBundle\Model\AbstractEnumList 
     */
    private $enumList;

    public function __construct(\Zuni\EnumBundle\Model\AbstractEnumList $enumList = null)
    {
        $this->enumList = $enumList;
    }

    public function transform($enumObject)
    {
        $value = "" ;
        
        if ($enumObject) {
            $value = $enumObject->getId();
        }
        
        return $value;
    }

    public function reverseTransform($id)
    {
        $enumObject = $this->enumList->getObject($id);

        if ($id && !$enumObject) {
            throw new TransformationFailedException(sprintf(
                    'Não foi possivel encontrar a enum "%s" !', $id
            ));
        }
        return $enumObject;
    }

}