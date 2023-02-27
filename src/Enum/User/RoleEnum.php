<?php

namespace App\Enum\User;

/**
 * Use this enum for manage app access
 * Register each role in security.yaml in the part role_hierarchy
 */
class RoleEnum
{
    const DEFAULT_ROLE = 'ROLE_USER';
    const ROLE_CLIENT = 'ROLE_CLIENT';

    public static array $roles = [
        // TODO : Add roles
    ];

    public static function getRole($key): string
    {
        if (!isset(static::$roles[$key])) {
            return "ROLE_USER";
        }

        return static::$roles[$key];
    }

    public static function getChoices(): array
    {
        return array_flip(static::$roles);
    }
}
