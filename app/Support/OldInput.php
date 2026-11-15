<?php

namespace App\Support;

final class OldInput
{
    public static function value(string $key, mixed $default = ''): string
    {
        $value = old($key, $default);

        return is_scalar($value) ? (string) $value : '';
    }
}
