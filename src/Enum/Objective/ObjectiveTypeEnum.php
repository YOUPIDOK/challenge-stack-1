<?php

namespace App\Enum\Objective;

class ObjectiveTypeEnum
{
    const AVERAGE_HOUR_SLEEP_PER_DAY = 'AVERAGE_HOUR_SLEEP_PER_DAY';
    const AVERAGE_CALORIC_SPENT_PER_DAY = 'AVERAGE_CALORIC_SPENT_PER_DAY';
    const AVERAGE_CALORIC_ASSIMILATE_PER_DAY = 'AVERAGE_CALORIC_SPENT_PER_DAY';
    const NB_HOUR_ACTIVITY_DURING_THE_PERIOD = 'NB_HOUR_ACTIVITY_DURING_THE_PERIOD';
    const AVERAGE_KILOMETER_TRAVELED = 'AVERAGE_KILOMETER_TRAVELED';
    const AVERAGE_CALORIC_DEFICIT_PER_DAY = 'AVERAGE_CALORIC_DEFICIT_PER_DAY';
    const WEIGHT_AT_THE_END_OF_THE_PERIOD = 'WEIGHT_AT_THE_END_OF_THE_PERIOD';

    public static array $types = [
        self::AVERAGE_HOUR_SLEEP_PER_DAY => "Moyenne d'heure de sommeil par jour",
        self::AVERAGE_CALORIC_SPENT_PER_DAY => "Moyenne calorique dépensée par jour",
        self::AVERAGE_CALORIC_ASSIMILATE_PER_DAY => "Moyenne calorique assimilée par jour",
        self::NB_HOUR_ACTIVITY_DURING_THE_PERIOD => "Heure d'actvité sur la période",
        self::AVERAGE_KILOMETER_TRAVELED => 'Moyenne kilomètre parcourus',
        self::AVERAGE_CALORIC_DEFICIT_PER_DAY => 'Moyenne déficite calorique par jour',
        self::WEIGHT_AT_THE_END_OF_THE_PERIOD => 'Poid à la fin de la période',
    ];

    public static function getType($key): string
    {
        if (!isset(static::$types[$key])) {
            return "Type inconnu ($key)";
        }

        return static::$types[$key];
    }

    public static function getChoices(): array
    {
        return array_flip(static::$types);
    }
}
