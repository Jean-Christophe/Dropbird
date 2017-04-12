<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 12/04/2017
 * Time: 10:22
 */

namespace TrajetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrajetsBundle\Entity\Collecteur;

class CollecteurController extends Controller{

    public function ajoutAction()
    {
        $collecteur = new Collecteur(0, "LE LIVREUR", "Hubert", "huber.lelivreur@dropbird.fr");
        $em= $this->getDoctrine()->getManager();

        $em->persist($collecteur);
        $em->flush();

        return $this->render('TrajetsBundle:Default:tests.html.twig',
                        array('collecteur' => $collecteur)
        );
    }
}