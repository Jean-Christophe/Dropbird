<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 13:33
 */

namespace TrajetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CarteController extends Controller
{

    public function affichageAction()
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TrajetsBundle:Consigne');
        $consignes = $repository->findAll();

        $repository = $em->getRepository('TrajetsBundle:Boutique');
        $boutiques = $repository->findAll();

        $repository = $em->getRepository('TrajetsBundle:Collecteur');
        $collecteur = $repository->find(1);

        $serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
        $jsonConsignes = $serializer->serialize($consignes, 'json');

        return $this->render('TrajetsBundle:Default:index.html.twig',
            ['consignes' => $jsonConsignes,
            'boutiques' => $boutiques,
            'collecteur' => $collecteur]
            );
    }

}