<?php

namespace App\Form\Objective;

use App\Entity\Data\ActivityTime;
use App\Entity\Food;
use App\Entity\Objective\Objective;
use App\Enum\Nutrition\MealTypeEnum;
use App\Enum\Objective\ObjectiveTypeEnum;
use App\Form\CustomType\EntitySelectChoicesType;
use App\Form\CustomType\SelectChoicesType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ObjectiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $minDate = new DateTime();
        $builder
            ->add('startAt', DateType::class, [
                'label' => 'Date de début',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Date de fin (optionel)',
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual(['value' => $minDate]),
                    new Callback([
                        'callback' => static function (?DateTime $value, ExecutionContextInterface $context) {
                            /** @var Objective $objective */
                            $objective = $context->getObject()->getParent()->getData();
                            if ($objective->getEndAt() !== null && $objective->isActive()) {
                                if ($objective->getEndAt() <= $objective->getStartAt()) {
                                    $context
                                        ->buildViolation('La date de fin de l\'objectif doit être inférieur à la date de début')
                                        ->atPath('endAt')
                                        ->addViolation();
                                }
                            }
                        }
                    ])
                ]
            ])
            ->add('label', TextType::class, [
                'label' => 'Nom de votre objectif',
                'required' => true
            ])
            ->add('type', SelectChoicesType::class, [
                'label' => 'Votre objectif',
                'required' => true,
                'choices' => ObjectiveTypeEnum::getChoices()
            ])
            ->add('objectiveValue', NumberType::class, [
                'label' => 'Valeur de l\'objectif',
                'required' => true,
                'scale' => 2,
                'constraints' => [
                    new Range( ['min' => 0]),
                    new Callback([
                        'callback' => static function (?int $value, ExecutionContextInterface $context) {
                            /** @var Objective $objective */
                            $objective = $context->getObject()->getParent()->getData();
                            switch ($objective->getType()) {
                                case 'AVERAGE_HOUR_SLEEP_PER_DAY':
                                    if ($value > 24) {
                                        $context
                                            ->buildViolation('L\'heure de sommeil moyenne ne peut pas être supérieur à 24h')
                                            ->atPath('objectiveValue')
                                            ->addViolation();
                                    }
                            }
                        }
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objective::class,
        ]);
    }
}
