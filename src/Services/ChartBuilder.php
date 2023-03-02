<?php

namespace App\Services;

use App\Entity\DailyReport;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weight;
use App\Entity\User\Client;
use DateTime;

class ChartBuilder
{
    const WEIGHT_AVERAGE = 'WEIGHT_AVERAGE';
    const SLEEP_TIME_AVERAGE = 'SLEEP_TIME_AVERAGE';
    const EAT_CALORIES = 'EAT_CALORIES';
    const SPENT_CALORIES = 'SPENT_CALORIES';

    private string   $type;
    private string   $chartTitle;
    private array    $data;
    private array    $labels = [];
    private array    $dataValues = [];
    private DateTime $minDateFilter;
    private   string $id;

    public function __construct() { }

    public function generate(string $type, string $chartTitle, array $data, DateTime $minDateFilter): self
    {
        $this->type = $type;
        $this->chartTitle = $chartTitle;
        $this->data = $data;
        $this->labels = [];
        $this->dataValues = [];
        $this->minDateFilter = $minDateFilter;
        $this->id = uniqid('chart');

        return $this;
    }

    public function getChartType()
    {
        switch ($this->type) {
            case self::WEIGHT_AVERAGE:
                return 'line';
            case self::SLEEP_TIME_AVERAGE:
                return 'bar';
        }
        return 'line';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData(): string
    {
        $this->generateLabelAndData();

        $config = [
            'labels'    => $this->labels,
            'datasets' => [
                [
                    'label' => $this->chartTitle,
//                    'backgroundColor' => 'transparent',
//                    'borderColor' => '#3B82F6',
                    'data'  => $this->dataValues,
                ]
            ]
        ];

        return json_encode($config);
    }

    public function isValid()
    {
        return count($this->data) > 1;
    }


    public function getOptions()
    {
        $options = [];

        $options['scales']['x'] = [
            'min' => $this->minDateFilter->format('Y-m-d'),
        ];

        // Moyenne temps de sommeil (Bar)
        if ($this->type === self::SLEEP_TIME_AVERAGE) {
            $options['scales']['y'] = [
                'min' => 0,
                'max' => 24,
            ];
        }

        return json_encode($options);
    }

    private function generateLabelAndData(): void
    {
        switch ($this->type) {
            case self::WEIGHT_AVERAGE:
                /** @var Weight $weight */
                foreach ($this->data as $weight) {
                    $this->dataValues[] = [
                        'x' => $weight->getDailyReport()->getDate()->format('Y-m-d'),
                        'y' => $weight->getWeight(),
                    ];
                    $this->labels[] = '';
                }
                break;
            case self::SLEEP_TIME_AVERAGE:
                /** @var DailyReport $dailyReport */
                foreach ($this->data as $dailyReport) {
                    $this->dataValues[] = [
                        'x' => $dailyReport->getDate()->format('Y-m-d'),
                        'y' => $dailyReport->getTotalSleepTime(),
                    ];
                    $this->labels[] = '';
                }
                break;
            case self::EAT_CALORIES :
                /** @var DailyReport $dailyReport */
                foreach ($this->data as $dailyReport) {
                    $this->dataValues[] = [
                        'x' => $dailyReport->getDate()->format('Y-m-d'),
                        'y' => $dailyReport->getTotalCaloriesEat(),
                    ];
                    $this->labels[] = '';
                }
                break;
            case self::SPENT_CALORIES:
                /** @var DailyReport $dailyReport */
                foreach ($this->data as $dailyReport) {
                    $this->dataValues[] = [
                        'x' => $dailyReport->getDate()->format('Y-m-d'),
                        'y' => $dailyReport->getTotalCaloriesSpent(),
                    ];
                    $this->labels[] = '';
                }
                break;
        }

    }
}