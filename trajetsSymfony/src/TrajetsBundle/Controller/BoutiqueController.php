<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 12/04/2017
 * Time: 10:51
 */

namespace TrajetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrajetsBundle\Entity\Boutique;

class BoutiqueController extends Controller
{
    public function ajoutAction()
    {
        $boutique = new Boutique(0, 'Confidence des Vignobles', '62 Boulevard Villebois Mareuil',
            35000, 'Rennes', 48.10497, -1.65055);
        $em= $this->getDoctrine()->getManager();

        $em->persist($boutique);
        $em->flush();

        return $this->render('TrajetsBundle:Default:tests.html.twig',
            array('boutique' => $boutique)
        );
    }
}