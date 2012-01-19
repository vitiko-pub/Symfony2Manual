<?php

namespace Test\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Test\NewsBundle\Entity\NewsLink;
use Test\NewsBundle\Form\NewsLinkType;

/**
 * NewsLink controller.
 *
 * @Route("/newslink")
 */
class NewsLinkController extends Controller
{
    /**
     * Lists all NewsLink entities.
     *
     * @Route("/", name="newslink")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TestNewsBundle:NewsLink')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a NewsLink entity.
     *
     * @Route("/{id}/show", name="newslink_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TestNewsBundle:NewsLink')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsLink entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new NewsLink entity.
     *
     * @Route("/new", name="newslink_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new NewsLink();
        $form   = $this->createForm(new NewsLinkType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new NewsLink entity.
     *
     * @Route("/create", name="newslink_create")
     * @Method("post")
     * @Template("TestNewsBundle:NewsLink:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new NewsLink();
        $request = $this->getRequest();
        $form    = $this->createForm(new NewsLinkType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newslink_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing NewsLink entity.
     *
     * @Route("/{id}/edit", name="newslink_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TestNewsBundle:NewsLink')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsLink entity.');
        }

        $editForm = $this->createForm(new NewsLinkType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing NewsLink entity.
     *
     * @Route("/{id}/update", name="newslink_update")
     * @Method("post")
     * @Template("TestNewsBundle:NewsLink:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TestNewsBundle:NewsLink')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsLink entity.');
        }

        $editForm   = $this->createForm(new NewsLinkType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newslink_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a NewsLink entity.
     *
     * @Route("/{id}/delete", name="newslink_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TestNewsBundle:NewsLink')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NewsLink entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('newslink'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
