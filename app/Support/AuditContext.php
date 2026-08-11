<?php

namespace App\Support;

class AuditContext
{
    protected static bool $disabled = false;

    public static function isDisabled(): bool
    {
        return self::$disabled;
    }

    public static function without(callable $callback)
    {
        $wasDisabled = self::$disabled;
        self::$disabled = true;

        try {
            return $callback();
        } finally {
            self::$disabled = $wasDisabled;
        }
    }
}