<?php

namespace App\Form;

use App\Entity\DailyReport;
use App\Entity\Food;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $min = $options['client']->getRegisteredAt()->format('Y-m-d');
        $max = (new DateTime())->format('Y-m-d');

        $builder
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Depuis le',
                'required' => true,
                'attr' => [
                    'min' => $min,
                    'max' => $max
                ]
            ])
            ->add('end', DateType::class, [
                'label' => "Jusqu'au",
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'min' => $min,
                    'max' => $max
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => Request::METHOD_GET,
            'client' => null
        ]);
    }
}
