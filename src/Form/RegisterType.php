<?php

namespace App\Form;

use App\Entity\User\User;
use App\Enum\User\GenderEnum;
use App\Form\CustomType\SelectChoicesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
            'required' => true,
        ]);

        $builder->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'required' => true,
        ]);

        $builder->add('lastname', TextType::class, [
            'label' => 'Nom',
            'required' => true,
        ]);

        $builder->add('gender', SelectChoicesType::class, [
            'label' => 'Genre',
            'required' => true,
            'choices' => GenderEnum::getChoices()
        ]);

        $builder->add('weight', NumberType::class, [
            'label' => 'Poids',
            'required' => true,
            'mapped' => false,
            'scale' => 2,
            'help' => 'En Kg',
            'constraints' => [
                new Range( ['min' => 20, 'max' => 400])
            ]
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => [
                'label' => 'Mot de passe',
            ],
            'second_options' => [
                'label' => 'Confirmation du mot de passe',
            ],
            'invalid_message' => 'Les mots de passe sont différents',
        ]);

        $builder->add('client', ClientType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}