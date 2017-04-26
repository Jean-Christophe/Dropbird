<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 26/04/2017
 * Time: 09:12
 */

namespace TrajetsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers la page admin
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            return $this->redirectToRoute('trajets_admin');

        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // Récupération d'une éventuelle erreur de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('TrajetsBundle:Admin:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
    ));
    }
}