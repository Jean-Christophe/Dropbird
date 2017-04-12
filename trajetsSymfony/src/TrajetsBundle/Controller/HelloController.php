<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 13:57
 */

namespace TrajetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    public function sayHelloAction($nom) {
        return new Response('Hello ' . $nom);
    }

}