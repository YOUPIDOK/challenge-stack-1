<?php

namespace App\Form\Data;

use App\Entity\Data\SleepTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SleepTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('asleepAt')
            ->add('awakeAt')
            ->add('time')
            ->add('dailyReport')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SleepTime::class,
        ]);
    }
}
