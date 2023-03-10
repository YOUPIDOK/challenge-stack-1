<?php

namespace App\Enum\Nutrition;

class MealTypeEnum
{
    const BREAKFAST = 'BREAKFAST';
    const LUNCH = 'LUNCH';
    const DINNER = 'DINNER';
    const SNACK = 'SNACK';

    public static array $types = [
        self::BREAKFAST => 'Breakfast',
        self::LUNCH => 'Lunch',
        self::DINNER => 'Dinner',
        self::SNACK => 'Snack',
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
