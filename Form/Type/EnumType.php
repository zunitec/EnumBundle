<?php

namespace Zuni\EnumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zuni\EnumBundle\Form\DataTransformer\EnumTransformer;

class EnumType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->resetModelTransformers();
        $builder->resetViewTransformers();
        $builder->addModelTransformer(new EnumTransformer($options['enumList']));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "enumList" => null,
        ));
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        $view->vars["enumList"] = $options['enumList'];
    }
    public function getParent()
    {
        return "choice";
    }

    public function getName()
    {
        return "enum";
    }

}