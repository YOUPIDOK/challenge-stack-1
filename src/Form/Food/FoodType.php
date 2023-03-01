<?php

namespace App\Form\Food;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Libellé',
                'required' => true
            ])
            ->add('calories', NumberType::class, [
                'label' => 'Calories',
                'required' => true,
                'scale' => 2,
                'help' => 'En kcal pour 100g'
            ])
            ->add('carbohydrates', NumberType::class, [
                'label' => 'Glucide',
                'required' => true,
                'scale' => 2,
                'help' => 'Pour 100g'
            ])
            ->add('lipids', NumberType::class, [
                'label' => 'Lipides',
                'required' => true,
                'scale' => 2,
                'help' => 'Pour 100g'
            ])
            ->add('proteins', NumberType::class, [
                'label' => 'Protéines',
                'required' => true,
                'scale' => 2,
                'help' => 'Pour 100g'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
        ]);
    }
}
