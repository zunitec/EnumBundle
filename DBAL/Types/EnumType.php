<?php

namespace Zuni\EnumBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Zuni\EnumBundle\Model\AbstractEnumObject;

class EnumType extends Type
{

    public function getName()
    {
        return "enum";
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * 
     * se tiver valor tem que ser instancia de AbstractEnum
     * 
     * @param type $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return type
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $id = null;
        if ($value && $this->isInstanceofAbstractEnum($value)) {
            $id = $value->getId();
        } else if($value && !$this->isInstanceofAbstractEnum($value)){
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for ENUM %s', $value, $this->getName()));
        }
        return $id;
    }

    private function isInstanceofAbstractEnum($value)
    {
        return $value instanceof AbstractEnumObject;
    }
}