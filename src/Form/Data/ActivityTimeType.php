<?php

namespace App\Form\Data;

use App\Entity\Data\ActivityTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            ->add('distance')
            ->add('time')
            ->add('activity')
            ->add('dailyReport')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivityTime::class,
        ]);
    }
}
