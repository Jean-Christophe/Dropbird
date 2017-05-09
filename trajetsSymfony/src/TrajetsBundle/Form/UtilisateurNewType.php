<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 04/05/2017
 * Time: 18:32
 */

namespace TrajetsBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class UtilisateurNewType extends UtilisateurType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('password', RepeatedType::class,
        ['type' => PasswordType::class,
            'label' => false,
            'options' => ['attr' => ['class' => 'form-control']],
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer']])
        ->add('roles', CollectionType::class,
            ['label' => 'Rôle',
                'entry_type' => ChoiceType::class,
                'entry_options' =>
                    ['label' => false,
                        'attr' => ['class' => 'form-control'],
                        'choices' => [
                            'Collecteur' => 'ROLE_COLLECTEUR',
                            'Administrateur' => 'ROLE_ADMIN'
                        ]
                    ]
            ]
        );
    }
}