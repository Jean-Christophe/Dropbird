<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 02/05/2017
 * Time: 13:57
 */

namespace TrajetsBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
                ['label' => 'Nom du lieu',
                    'attr' => ['class' => 'form-control']])
            ->add('adresse', TextType::class,
                ['attr' =>['class' => 'form-control']])
            ->add('cpo', IntegerType::class,
                ['label' => 'Code postal',
                    'attr' => ['class' => 'form-control']])
            ->add('ville', TextType::class,
                ['attr' =>['class' => 'form-control']])
            ->add('latitude', TextType::class,
                ['attr' =>['class' => 'form-control']])
            ->add('longitude', TextType::class,
                ['attr' =>['class' => 'form-control']])
            ->add('label', ChoiceType::class,
                ['label' => 'Type de lieu',
                    'attr' =>['class' => 'form-control'],
                    'choices' => [
                        'Boutique' => 'B',
                        'Consigne' => 'C'
                    ]])
            ->add('ajouter', SubmitType::class,
                ['attr' => ['class' => 'btn btn-lg btn-success']]);
    }

    public function getBlockPrefix()
    {
        return 'lieu';
    }
}