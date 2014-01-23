<?php

namespace Zuni\EnumBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EnumListener
{

    /**
     * Carregar a enum ao buscar objeto do banco
     * 
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();
        $reader = new AnnotationReader();
        $reflection = new ReflectionClass($entity);

        if ($this->hasEnumAnnotation($reader, $reflection, $entity)) {
            /* @var $reflectionProperty ReflectionProperty */
            foreach ($args->getEntityManager()->getClassMetadata($reflection->getName())->getReflectionProperties() as $reflectionProperty) {
                $classAnnotation = $reader->getPropertyAnnotation($reflectionProperty, 'Zuni\EnumBundle\Annotation\Enum');
                if ($classAnnotation) {
                    $value = PropertyAccess::getPropertyAccessor()->getValue($entity, $reflectionProperty->getName());
                    $reflectionEnum = new ReflectionClass($classAnnotation->enumList);
                    /* @var $enumList AbstractEnumList */
                    $enumList = $reflectionEnum->getMethod("getInstance")->invoke(null);
                    PropertyAccess::getPropertyAccessor()->setValue($entity, $reflectionProperty->getName(), $enumList->getObject($value));
                }
            }
        }
    }

    /**
     * Se a classe possui a anotação de HasEnum
     * 
     * @param AnnotationReader $reader
     * @param ReflectionClass $reflection
     * @return boolean
     */
    private function hasEnumAnnotation($reader, $reflection, $entity)
    {

        $classAnnotation = $reader->getClassAnnotation($reflection, 'Zuni\EnumBundle\Annotation\HasEnum');
        $possuiAnotacao = $classAnnotation && $classAnnotation->has;

        //POG pois algumas classes chegam aqui como filhas de *proxy*
        if (!$possuiAnotacao) {
            $pai = get_parent_class($entity);
            if ($pai) {
                $reflection = new ReflectionClass($pai);
                $classAnnotation = $reader->getClassAnnotation($reflection, 'Zuni\EnumBundle\Annotation\HasEnum');
                $possuiAnotacao = $classAnnotation && $classAnnotation->has;
            }
        }

        return $possuiAnotacao;
    }

}
