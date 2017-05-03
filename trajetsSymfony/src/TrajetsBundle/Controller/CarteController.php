<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 13:33
 */

namespace TrajetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CarteController extends Controller
{
    public function affichageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TrajetsBundle:Lieu');
        $boutiques = $repository->findBy(array('label' => 'B'));
        $consignes = $repository->findBy(array('label' => 'C'));

        return $this->render('TrajetsBundle:Default:index.html.twig',
            ['consignes' => $consignes,
            'boutiques' => $boutiques]
            );
    }

}