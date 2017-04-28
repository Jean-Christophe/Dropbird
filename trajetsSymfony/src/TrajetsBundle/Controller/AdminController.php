<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 25/04/2017
 * Time: 16:06
 */

namespace TrajetsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use TrajetsBundle\Entity\Utilisateur;

class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TrajetsBundle:Lieu');
        $boutiques = $repository->findBy(array('label' => 'B'));
        $consignes = $repository->findBy(array('label' => 'C'));

        $repository = $em->getRepository('TrajetsBundle:Utilisateur');
        $utilisateurs = $repository->findAll();

        return $this->render('TrajetsBundle:Admin:index.html.twig',
            ['boutiques' => $boutiques,
                'consignes' => $consignes,
                'utilisateurs' => $utilisateurs]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction()
    {

    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction()
    {

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction()
    {

    }
}