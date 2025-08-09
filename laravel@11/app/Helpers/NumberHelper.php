<?php
// Author: DarCasanova
// Date: 04/22/2025
// Time: 10:22pm

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format a number with custom decimals, separator, and thousands separator.
     */
    public static function format(
        float|int|string $value,
        int $decimals = 2,
        string $decimalSeparator = '.',
        string $thousandsSeparator = ','
    ): string {
        return number_format((float) $value, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    /**
     * Format a number as currency.
     */
    public static function formatCurrency(
        float|int|string $value,
        string $currencySymbol = '₱',
        int $decimals = 2
    ): string {
        return $currencySymbol . self::format($value, $decimals);
    }

    /**
     * Format a quantity without decimals if whole, or show up to 3 decimals if not.
     */
    public static function formatQuantity(float|int|string $value): string
    {
        return fmod((float) $value, 1) === 0.0
            ? number_format($value, 0)
            : number_format($value, 3, '.', '');
    }
}
