<?php

namespace App\Services;

use App\Entity\Data\Weight;
use App\Entity\User\Client;
use DateTime;

class ChartBuilder
{
    const LINE_TYPE = 'line';

    private string   $type;
    private string   $chartTitle;
    private array    $data;
    private array    $labels = [];
    private array    $dataValues = [];
    private DateTime $minDateFilter;

    public function __construct() { }


    public function generate(string $type, string $chartTitle, array $data, DateTime $minDateFilter): self
    {
        $this->minDateFilter = $minDateFilter;
        $this->type       = $type;
        $this->chartTitle = $chartTitle;
        $this->data = $data;
        $this->labels = [];
        $this->dataValues = [];

        return $this;
    }

    public function getType()
    {
        return $this->type;
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
        return count($this->data) > 0;
    }


    public function getOptions()
    {
        $options = [];

        $options['scales']['x'] = [
            'min' => $this->minDateFilter->format('Y-m-d'),
        ];

        dump($options);

        return json_encode($options);
    }

    private function generateLabelAndData(): void
    {
        switch ($this->data[0]::class) {
            case Weight::class:
                /** @var Weight $weight */
                foreach ($this->data as $weight) {
                    $this->dataValues[] = [
                        'x' => $weight->getDailyReport()->getDate()->format('Y-m-d'),
                        'y' => $weight->getWeight(),
                    ];
                    $this->labels[] = '';
                }
                break;
        }

    }
}