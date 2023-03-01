<?php

namespace App\Form\Data;

use App\Entity\Data\SleepTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SleepTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('asleepAt', DateType::class, [
                'label' => 'Heure d\'endormisement',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('awakeAt', DateType::class, [
                'label' => 'Heure de réveil',
                'required' => true,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SleepTime::class,
        ]);
    }
}
