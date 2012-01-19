<?php


namespace Test\NewsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;




class NewsLinkAdmin extends Admin
{


    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        //$formMapper->add('news', 'sonata_type_model', array('label' => 'Новость', 'required' => true), array('edit' => 'list'));
        //            $formMapper->add('post', 'sonata_type_admin', array(), array('edit' => 'inline'));


        $formMapper

                ->add('url', null, array('label' => 'URL', 'required' => true))
                ->add('text', null, array('label' => 'Описание'))//        ->add('news', null, array('label' => 'Новость'))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {


        $listMapper
                ->addIdentifier('url')

                ->add('news', null, array('label' => 'Новость'))
                ->add('text', null, array('label' => 'Описание'));
    }


}
