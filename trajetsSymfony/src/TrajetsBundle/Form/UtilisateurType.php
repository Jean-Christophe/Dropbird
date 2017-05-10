<?php

namespace TrajetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
                ['label' => 'Nom',
                    'attr' => ['class' => 'form-control']])
            ->add('prenom', TextType::class,
                ['label' => 'Prénom',
                    'attr' => ['class' => 'form-control']])
            ->add('email', EmailType::class,
                ['label' => 'E-mail',
                    'attr' => ['class' => 'form-control']])
            ->add('enabled', ChoiceType::class,
                ['label' => 'Actif',
                    'attr' => ['class' => 'form-control'],
                    'choices' => [
                        'Oui' => true,
                        'Non' => false
                        ]
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrajetsBundle\Entity\Utilisateur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trajetsbundle_utilisateur';
    }


}
