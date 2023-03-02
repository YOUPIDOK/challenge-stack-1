<?php

namespace App\Form\Data;

use App\Entity\Activity;
use App\Entity\Data\ActivityTime;
use App\Entity\Food;
use App\Form\CustomType\EntitySelectChoicesType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ActivityTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ActivityTime $activityTime */
        $activityTime = $options['data'];
        $dailyReport = $activityTime->getDailyReport();
        $minDate = (clone $dailyReport->getDate())->modify('-1 day');
        $maxDate = (clone $dailyReport->getDate())->modify('+23 hour')->modify('+59 minute');

        $builder
            ->add('startAt', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual(['value' => $maxDate]),
                    new GreaterThanOrEqual(['value' => $minDate]),
                ]
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Date de fin',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual(['value' => $maxDate]),
                    new GreaterThanOrEqual(['value' => $minDate]),
                    new Callback([
                        'callback' => static function (?DateTime $value, ExecutionContextInterface $context) {
                            /** @var ActivityTime $activityTime */
                            $activityTime = $context->getObject()->getParent()->getData();
                            if ($activityTime->getEndAt() < $activityTime->getStartAt()) {
                                $context
                                    ->buildViolation('La date de fin de l\'activité doit être inférieur à la date de début')
                                    ->atPath('endAt')
                                    ->addViolation();
                            }
                        }
                    ])
                ]
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance',
                'required' => false,
                'scale' => 2,
                'help' => 'En mètres',
                'constraints' => [
                    new Range(['min' => 0])
                ]
            ])
            ->add('activity', EntitySelectChoicesType::class, [
                'class' => Activity::class,
                'label' => 'Activité'
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
