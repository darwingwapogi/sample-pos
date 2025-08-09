<?php
// Author: DarCasanova
// Date: 03/30/2025
// Time: 04:05pm
// Description: Utility class providing reusable date format constants and helper methods for date formatting.
// Note: Additional date-related utility methods can be added as needed.

namespace App\Common\Utility;

use Carbon\Carbon;

class DateUtil
{
    // Date format constants
    public const FORMAT_FULL = 'Y-m-d H:i:s';
    public const FORMAT_DATE_ONLY = 'Y-m-d';
    public const FORMAT_TIME_ONLY = 'H:i:s';
    public const FORMAT_MONTH_DAY_YEAR = 'm/d/Y';
    public const FORMAT_DAY_MONTH_YEAR = 'd/m/Y';
    public const FORMAT_YEAR_MONTH_DAY = 'Y/m/d';
    public const FORMAT_ISO_8601 = 'c';
    public const FORMAT_RFC_2822 = 'r';
    public const FORMAT_CUSTOM_SHORT = 'd-M-Y';
    public const FORMAT_CUSTOM_LONG = 'l, d F Y';
    public const FORMAT_DATE_TIME_12_HOUR = 'm/d/Y h:i A';
    public const FORMAT_DATE_TIME_24_HOUR = 'm/d/Y H:i';
    public const FORMAT_DAY_DATE_TIME_12_HOUR = 'l, d M Y h:i A';
    public const FORMAT_DAY_DATE_TIME_24_HOUR = 'l, d M Y H:i';

    /**
     * Format a given date to the standard hardware system format.
     *
     * @param string|\DateTimeInterface|null $date
     * @param string $format
     * @return string|null
     */
    public static function formatDate($date, $format = self::FORMAT_FULL): ?string
    {
        if (!$date) {
            return null;
        }

        return Carbon::parse($date)->format($format);
    }
}