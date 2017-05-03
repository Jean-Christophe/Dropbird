<?php

namespace TrajetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
                    ]]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrajetsBundle\Entity\Lieu'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trajetsbundle_lieu';
    }


}
