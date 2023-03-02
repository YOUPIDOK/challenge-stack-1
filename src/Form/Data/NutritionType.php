<?php

namespace App\Form\Data;

use App\Entity\Data\Nutrition;
use App\Entity\Food;
use App\Enum\Nutrition\MealTypeEnum;
use App\Form\CustomType\EntitySelectChoicesType;
use App\Form\CustomType\SelectChoicesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class NutritionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mealType', SelectChoicesType::class, [
                'label' => 'Type de repas',
                'required' => true,
                'choices' => MealTypeEnum::getChoices()
            ])
            ->add('food', EntitySelectChoicesType::class, [
                'label' => 'Aliment / Plat',
                'class' => Food::class
            ])
            ->add('foodWeight', NumberType::class, [
                'label' => 'Poids',
                'required' => true,
                'scale' => 2,
                'help' => 'En grammes',
                'constraints' => [
                    new Range(['min' => 0])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nutrition::class,
        ]);
    }
}
