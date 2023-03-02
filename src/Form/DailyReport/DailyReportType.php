<?php

namespace App\Form\DailyReport;

use App\Entity\DailyReport;
use App\Entity\Objective\Objective;
use App\Repository\DailyReportRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DailyReportType extends AbstractType
{
    public function __construct(private DailyReportRepository $dailyReportRepository)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DailyReport $dailyReport */
        $dailyReport = $options['data'];
        $client = $dailyReport->getClient();
        $minDate = (clone $client->getBirthdate());
        $maxDate = (new DateTime("midnight"))->modify('+23 hour')->modify('+59 minute');
        $dailyReportRepository = $this->dailyReportRepository;

        $builder
            ->add('date', DateType::class, [
                'label' => 'Date du rapport',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual(['value' => $minDate]),
                    new LessThanOrEqual(['value' => $maxDate]),
                    new Callback([
                        'callback' => static function (?DateTime $value, ExecutionContextInterface $context) use ($dailyReportRepository) {
                            /** @var DailyReport $dailyReport */
                            $dailyReport = $context->getObject()->getParent()->getData();
                            $dailyReportExist = $dailyReportRepository->findBy(['client' => $dailyReport->getClient(), 'date' => $value]);

                            if (count($dailyReportExist) >= 1) {
                                $context
                                    ->buildViolation('Un rapport existe déjà pour cette date')
                                    ->atPath('date')
                                    ->addViolation();
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
            'data_class' => DailyReport::class,
        ]);
    }
}
