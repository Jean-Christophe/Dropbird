<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 25/04/2017
 * Time: 16:06
 */

namespace TrajetsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TrajetsBundle:Lieu');
        $boutiques = $repository->findBy(array('label' => 'B'));
        $consignes = $repository->findBy(array('label' => 'C'));

        return $this->render('TrajetsBundle:Admin:list.html.twig',
            ['boutiques' => $boutiques,
                'consignes' => $consignes]);
    }
}