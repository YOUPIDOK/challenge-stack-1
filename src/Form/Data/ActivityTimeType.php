<?php

namespace App\Form\Data;

use App\Entity\Activity;
use App\Entity\Data\ActivityTime;
use App\Entity\Food;
use App\Form\CustomType\EntitySelectChoicesType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

class ActivityTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', DateType::class, [
                'label' => 'Date de début',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Date de fin',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance',
                'required' => false,
                'scale' => 2,
                'help' => 'En métres',
                'constraints' => [
                    new Range( ['min' => 0])
                ]
            ])
            ->add('activity', EntitySelectChoicesType::class, [
                'class' => Activity::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivityTime::class,
        ]);
    }
}
