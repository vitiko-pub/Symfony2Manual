<?php

namespace Test\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class NewsLinkType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('url')
                ->add('text');
    }

    public function getName()
    {
        return 'newsLinkType';
    }


    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Test\NewsBundle\Entity\NewsLink',
        );
    }
}
