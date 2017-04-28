<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 27/04/2017
 * Time: 10:43
 */

namespace TrajetsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TrajetsBundle\Entity\Utilisateur;

class TestController extends Controller
{
    public function indexAction()
    {
        $utilisateur1 = new Utilisateur(0, 'LECOLLECTEUR', 'Hubert', 'hubert.lecollecteur@dropbird.fr',
            'hubert', true);
        $utilisateur1->setRoles(array('ROLE_COLLECTEUR'));
        $utilisateur2 = new Utilisateur(0, 'LADMIN', 'Martine', 'martine.ladmin@dropbird.fr',
            'martine', true);
        $utilisateur2->setRoles(array('ROLE_ADMIN'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($utilisateur1);
        $em->persist($utilisateur2);
        $em->flush();

        return new Response('OK');
    }
}