<?php

namespace Test\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('title', 'text', array('label' => 'Заголовок'))
                ->add('announce', 'textarea', array('label' => 'Анонс'))
                ->add('text', 'textarea', array('label' => 'Текст'))
                ->add('pubDate', null, array('label' => 'Дата новости'))
                ->add('newsCategory', null, array('label' => 'Категория'))
                ->add('newsLinks', 'collection', array(
                                                      'label' => 'Ссылки к новости',
                                                      'type' => new NewsLinkType(),
                                                      'allow_add' => true,
                                                      'allow_delete' => true,
                                                      'prototype' => true
                                                 ));
    }

    public function getName()
    {
        return 'news';
    }
}
