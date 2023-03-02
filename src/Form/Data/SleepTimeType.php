<?php

namespace App\Form\Data;

use App\Entity\Data\ActivityTime;
use App\Entity\Data\SleepTime;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SleepTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SleepTime $sleepTime */
        $sleepTime = $options['data'];
        $dailyReport = $sleepTime->getDailyReport();
        $minDate = (clone $dailyReport->getDate())->modify('-1 day');
        $maxDate = (clone $dailyReport->getDate())->modify('+23 hour')->modify('+59 minute');

        $builder
            ->add('asleepAt', DateTimeType::class, [
                'label' => 'Heure du coucher',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual(['value' => $maxDate]),
                    new GreaterThanOrEqual(['value' => $minDate]),
                ]
            ])
            ->add('awakeAt', DateTimeType::class, [
                'label' => 'Heure du réveil',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual(['value' => $maxDate]),
                    new GreaterThanOrEqual(['value' => $minDate]),
                    new Callback([
                        'callback' => static function (?DateTime $value, ExecutionContextInterface $context) {
                            /** @var SleepTime $sleepTime */
                            $sleepTime = $context->getObject()->getParent()->getData();
                            if ($sleepTime->getAwakeAt() < $sleepTime->getAsleepAt()) {
                                $context
                                    ->buildViolation('La date de coucher doit être inférieur à la date de lever')
                                    ->atPath('awakeAt')
                                    ->addViolation();
                            }
                        }
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SleepTime::class,
        ]);
    }
}
