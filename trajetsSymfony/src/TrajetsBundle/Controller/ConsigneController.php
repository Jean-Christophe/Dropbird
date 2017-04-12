<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 12/04/2017
 * Time: 11:17
 */

namespace TrajetsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrajetsBundle\Entity\Consigne;

class ConsigneController extends Controller
{
    public function ajoutAction()
    {
        $consigne = new Consigne(0, 'Métro République', 35000, 'Rennes', 48.103497, -1.672278);
        $em= $this->getDoctrine()->getManager();

        $em->persist($consigne);
        $em->flush();

        return $this->render('TrajetsBundle:Default:tests.html.twig',
            array('consigne' => $consigne)
        );
    }
}