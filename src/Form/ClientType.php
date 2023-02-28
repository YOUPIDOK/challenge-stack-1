<?php

namespace App\Form;

use App\Entity\User\Client;
use App\Entity\User\User;
use App\Enum\User\GenderEnum;
use App\Form\CustomType\SelectChoicesType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('birthdate', DateTimeType::class, [
            'label' => 'Date de naissance',
            'required' => true,
            'widget' => 'single_text',
            'constraints' => [
                new LessThanOrEqual(['value' => new DateTime('-15years'), 'message' => 'Minimum 15 ans'])
            ]
        ]);

        $builder->add('height', NumberType::class, [
            'label' => 'Taille',
            'required' => true,
            'scale' => 2,
            'help' => 'En cm',
            'constraints' => [
                new Range( ['min' => 60, 'max' => 260])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class
        ]);
    }
}