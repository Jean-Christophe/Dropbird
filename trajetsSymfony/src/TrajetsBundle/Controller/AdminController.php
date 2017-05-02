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
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TrajetsBundle\Entity\Lieu;
use TrajetsBundle\Entity\Utilisateur;
use TrajetsBundle\Form\LieuType;

class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TrajetsBundle:Lieu');
        $boutiques = $repository->findBy(array('label' => 'B'));
        $consignes = $repository->findBy(array('label' => 'C'));

        $repository = $em->getRepository('TrajetsBundle:Utilisateur');
        $utilisateurs = $repository->findAll();

        return $this->render('TrajetsBundle:Admin:index.html.twig',
            ['boutiques' => $boutiques,
                'consignes' => $consignes,
                'utilisateurs' => $utilisateurs]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function ajoutLieuAction(Request $request)
    {
        $lieu = new Lieu();

        $form = $this->createForm(LieuType::class, $lieu);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $lieu = $form->getData();

                try{
                    $latitude = $lieu->getLatitude();
                    $latitude = str_replace(',', '.', $latitude);
                    $latitude = floatval($latitude);
                    if($latitude == 0){
                        throw new \Exception('La latitude n\'est pas valide');
                    }

                    $longitude = $lieu->getLongitude();
                    $longitude = str_replace(',', '.', $longitude);
                    $longitude = floatval($longitude);
                    if($longitude == 0){
                        throw new \Exception('La longitude n\'est pas valide');
                    }
                    $lieu->setLatitude($latitude);
                    $lieu->setLongitude($longitude);
                }catch (\Exception $e)
                {
                    $form->get('latitude')->addError(new FormError($e->getMessage()));
                    throw new \Exception('Les coordonnées du nouveau lieu sont incorrectes.');
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($lieu);
                $em->flush();
                $message = 'Le nouveau lieu a bien été enregistré.';

                unset($lieu);
                unset($form);
                $lieu = new Lieu();
                $form = $this->createForm(LieuType::class, $lieu);
            } catch (\Exception $e)
            {
                $message = $e->getMessage();
            }

            return $this->render('TrajetsBundle:Admin:ajout_lieu.html.twig',
                ['form' => $form->createView(),
                    'message' => $message]
            );
        }

        return $this->render('TrajetsBundle:Admin:ajout_lieu.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction()
    {

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction()
    {

    }
}