<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 04/05/2017
 * Time: 18:38
 */

namespace TrajetsBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class UtilisateurEditType extends UtilisateurType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder/*->add('roles', CollectionType::class,
        ['mapped' => false,
            'label' => 'Rôle',
            'entry_type' => ChoiceType::class,
            'entry_options' =>
                ['label' => false,
                    'attr' => ['class' => 'form-group'],
                    'expanded' => true,
                    'choices' => [
                        'Collecteur' => 'ROLE_COLLECTEUR',
                        'Administrateur' => 'ROLE_ADMIN'
                    ]
                ]
        ]
        )*/
        ->add('role_utilisateur', ChoiceType::class, [
            'mapped' => false,
            'label' => 'Rôle',
            'attr' => ['class' => 'form-control'],
            'choices' => [
                'Collecteur' => 'ROLE_COLLECTEUR',
                'Administrateur' => 'ROLE_ADMIN'
            ]
        ]);
    }
}