<?php

namespace Test\NewsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class NewsAdmin extends Admin
{
    /**
     * Метод вызывается перед обновлением записи
     * @param  $news Редактируемый объект
     * @return void
     */
    public function preUpdate($news)
    {
        //Создаем новый экземпляр редактируемой сущности
        $emptyObj = $this->getNewInstance();

        //Создаем форму, которая описана в методе сonfigureFormFields, привязываем к ней пустой объект
        //наполняем пустой объект данными из запроса - это позволяет добиться того, что
        //порядок привязанных NewsLink будет таким, как определено в html-форме
        //(учитывая возможные перемещения строк таблицы с полями редактирования NewsLink)

        //В отличии от порядка записей NewsLink редактируемого объекта - он такой, как возвращает Doctrine
        $this->getForm()->setData($emptyObj)->bindRequest($this->getRequest());

        $newLinkPos = array();
        //Запоминаем положение NewsLink
        foreach ($emptyObj->getNewsLinks() as $link) $newLinkPos[] = $link->getUrl();
        $newLinkPos = array_flip($newLinkPos);

        //Выставляем позиции для редактируемого объекта
        foreach ($news->getNewsLinks() as $pos => $link)
            $link->setPos($newLinkPos[$link->getUrl()]);
    }

    /**
     * Конфигурация отображения записи
     *
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('id', null, array('label' => 'Идентификатор'))
                ->add('title', null, array('label' => 'Заголовок'))
                ->add('announce', null, array('label' => 'Анонс'))
                ->add('text', null, array('label' => 'Текст'))
                ->add('pubDate', null, array('label' => 'Дата публикации'))
                ->add('newsLinks', null, array('label' => 'Ссылки к новости'))
                ->add('newsCategory', null, array('label' => 'Идентификатор'));
    }

    /**
     * Конфигурация формы редактирования записи
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('title', null, array('label' => 'Заголовок'))
                ->add('announce', null, array('label' => 'Анонс'))
                ->add('text', null, array('label' => 'Текст'))
                ->add('pubDate', null, array('label' => 'Дата публикации'))

        //by_reference используется для того чтобы при трансформации данных запроса в объект сущности
        //которую выполняет Symfony Form Framework, использовался setter сущности News::setNewsLinks
                ->add('newsLinks', 'sonata_type_collection',
                      array('label' => 'Ссылки', 'by_reference' => false),
                      array(
                           'edit' => 'inline',
                          //В сущности NewsLink есть поле pos, отражающее положение ссылки в списке
                          //указание опции sortable позволяет менять положение ссылок в списке перетаскиваением
                           'sortable' => 'pos',
                           'inline' => 'table',
                      ))
                ->add('newsCategory', null, array('label' => 'Категория'))
                ->setHelps(array(
                                'title' => 'Подсказка по заголовку',
                                'pubDate' => 'Дата публикации новости на сайте'
                           ));
    }

    /**
     * Конфигурация списка записей
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->addIdentifier('title', null, array('label' => 'Заголовок'))
                ->add('pubDate', null, array('label' => 'Дата публикации'))
                ->add('newsCategory', null, array('label' => 'Категория'));
    }

    /**
     * Поля, по которым производится поиск в списке записей
     *
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title', null, array('label' => 'Заголовок'));
    }

    /**
     * Конфигурация левого меню при отображении и редатировании записи
     *
     * @param \Knp\Menu\ItemInterface $menu
     * @param $action
     * @param null|\Sonata\AdminBundle\Admin\Admin $childAdmin
     *
     * @return void
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, Admin $childAdmin = null)
    {
        if ($action == 'edit' || $action == 'show')
        $menu->addChild(
            $action == 'edit' ? 'Просмотр новости' : 'Редактирование новости',
            array('uri' => $this->generateUrl(
                $action == 'edit' ? 'show' : 'edit', array('id' => $this->getRequest()->get('id'))))
        );
    }
}