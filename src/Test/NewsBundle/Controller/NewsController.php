<?php

namespace Test\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Test\NewsBundle\Entity\News;
use Test\NewsBundle\Entity\NewsCategory;
use Test\NewsBundle\Form\NewsType;


use Test\NewsBundle\Entity\Search\News as SearchNews;
use Test\NewsBundle\Form\Search\NewsType as SearchNewsType;


/**
 * News controller.
 *
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * Список новостей
     *
     * @Route("/", name="news")
     * @Template()
     */
    public function indexAction()
    {
        //Создаем доменный объект, в котором хранятся параметры поиска
        $searchNews = new SearchNews();

        $searchNews->availability = 'morning';
        //Создаем форму поиска

        $em = $this->getDoctrine()->getEntityManager();
        $searchForm = $this->createForm(new SearchNewsType($em), $searchNews);


        $searchForm->bindRequest($this->getRequest());

        // var_dump ($searchForm);

        // var_dump ($searchNews);


        //Установка категории по умолчанию
        if ($searchNews->category === null) {
            $searchNews->category = $this->getDoctrine()->getEntityManager()->getRepository('TestNewsBundle:NewsCategory')->find(1);
            $searchForm->setData($searchNews);
        }
        //var_dump ($searchNews);
        //$searchNews->availability = 'morning';

        //  $searchForm->setData( $searchNews);

        //Создаем построитель запросов Doctrine
        $qb = $em->getRepository('TestNewsBundle:News')->createQueryBuilder('n');

        //Добавляем к запросу left join c сущностью "Категория"
        //при выводе в списке названия категории нового запроса не будет
        $qb->select('n,c')->leftJoin('n.newsCategory', 'c')->orderBy('n.pubDate');

        //Если есть строка поиска - добавляем ИЛИ условие LIKE пои полям title, announce, text
        if ($searchNews->search) {
            foreach (array('n.title', 'n.announce', 'n.text') as $field)
                $qb->orWhere($qb->expr()->like($field, $qb->expr()->literal('%' . $searchNews->search . '%')));
        }

        if ($searchNews->year) {
            $qb->andWhere("SUBSTRING(n.pubDate,1,4) = '" . $searchNews->year . "'");
        }

        //Категория новостей
        // if ($searchNews->category) $qb->andWhere($qb->expr()->eq('c.id', $searchNews->category));


        //Дата С которой искать новости
        if ($searchNews->dateFrom) $qb->andWhere($qb->expr()->gt('n.pubDate', $qb->expr()->literal($searchNews->dateFrom->format('Y-m-d'))));
        //Дата До которой искать новости
        if ($searchNews->dateTo) $qb->andWhere($qb->expr()->lt('n.pubDate', $qb->expr()->literal($searchNews->dateTo->format('Y-m-d'))));


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1) /*page number*/,
            2/*limit per page*/
        );

        return array('pagination' => $pagination, 'search_form' => $searchForm->createView());
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="news_show")
     * @Template()
     */
    public function showAction(News $news)
    {
        $deleteForm = $this->createDeleteForm($news->getId());

        return array(
            'entity' => $news,
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="news_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new News();
        $form = $this->createForm(new NewsType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="news_create")
     * @Method("post")
     * @Template("TestNewsBundle:News:new.html.twig")
     */
    public function createAction()
    {
        $entity = new News();
        $request = $this->getRequest();
        $form = $this->createForm(new NewsType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();


            foreach ($entity->getNewsLinks() as $link)
            {
                $link->setNews($entity);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_show', array('id' => $entity->getId())));

        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="news_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TestNewsBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createForm(new NewsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="news_update")
     * @Method("post")
     * @Template("TestNewsBundle:News:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TestNewsBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }


        $beforeSaveLinks = $currentLinkIds = array();
        foreach ($entity->getNewsLinks() as $link)
            $beforeSaveLinks [$link->getId()] = $link;


        $editForm = $this->createForm(new NewsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {

            foreach ($entity->getNewsLinks() as $link)
            {
                $link->setNews($entity);
                //Если ссылка - не только что занесенная (у нее есть id)
                if ($link->getId()) $currentLinkIds[] = $link->getId();
            }

            $em->persist($entity);

            //Если ссылка которая была до сохранения отсутствует в текущем наборе - удаляем ее
            foreach ($beforeSaveLinks as $linkId => $link)
                if (!in_array($linkId, $currentLinkIds)) $em->remove($link);

            $em->flush();

            return $this->redirect($this->generateUrl('news_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}/delete", name="news_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TestNewsBundle:News')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('news'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                ->add('id', 'hidden')
                ->getForm();
    }
}
