<?php

namespace Test\NewsBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class NewsType extends AbstractType
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }


    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('search', 'search', array('required' => false, 'label' => 'Поиск '))
                ->add('category', 'entity', array(
                                                 'label' => 'Категория',
                                                 'required' => false,
                                                 'class' => 'Test\\NewsBundle\\Entity\\NewsCategory'))
                ->add('dateFrom', 'date', array(
                                               'label' => 'с',
                                               'widget' => 'single_text',
                                               'format' => 'yyyy-MM-dd',
                                               'attr' => array('class' => 'date'),
                                               'required' => false))
                ->add('dateTo', 'date', array(
                                             'label' => 'по',
                                             'widget' => 'single_text',
                                             'format' => 'yyyy-MM-dd',
                                             'attr' => array('class' => 'date'),
                                             'required' => false))


                ->add('year', 'choice', array(
                                             'choice_list' => new  YearChoiceList($this->em),
                                             'label' => 'Год',
                                             'required' => false,
                                        ));
    }


    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
        );
    }

    function getName()
    {
        return 'n';
    }
}


class YearChoiceList implements \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    function getChoices()
    {
        $years = $this->em->createQuery(
            "SELECT DISTINCT SUBSTRING (n.pubDate,1,4) AS year FROM TestNewsBundle:News n ORDER BY year")->getResult();

        $arr = array();
        foreach ($years as $y) $arr[$y['year']] = $y['year'];
        return $arr;
    }
}
