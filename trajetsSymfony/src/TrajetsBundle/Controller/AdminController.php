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

class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('TrajetsBundle:Admin:index.html.twig');
        }
        else if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('lieu_index');
        }
        return $this->redirectToRoute('trajets_homepage');

    }
}