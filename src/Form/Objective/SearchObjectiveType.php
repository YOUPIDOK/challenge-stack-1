<?php

namespace App\Form\Objective;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchObjectiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom de votre objectif',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un objectif'
                ]
            ])
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Depuis le',
                'required' => false
            ])
            ->add('end', DateType::class, [
                'label' => "Jusqu'au",
                'widget' => 'single_text',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => Request::METHOD_GET,
        ]);
    }
}
