<?php

namespace App\Form;

use App\Entity\Data\Weight;
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

class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
            'disabled' => true,
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
            'disabled' => true,
            'choices' => GenderEnum::getChoices()
        ]);

        /** @var Weight $weight */
        $weight = $options['weight'];
        if ($weight !== null) {
            $builder->add('weight', NumberType::class, [
                'label' => 'Poids',
                'disabled' => true,
                'data' => $weight->getWeight(),
                'mapped' => false,
                'scale' => 2,
                'help' => 'En Kg',
            ]);
        }

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => false,
            'first_options' => [
                'label' => 'Mot de passe',
            ],
            'second_options' => [
                'label' => 'Confirmation du mot de passe',
            ],
            'invalid_message' => 'Les mots de passe sont différents',
        ]);

        $builder->add('client', ClientType::class, [
            'disable_birthdate' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'weight' => null
        ]);
    }
}