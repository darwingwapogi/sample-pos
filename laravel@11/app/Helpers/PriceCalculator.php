<?php
// Author: DarCasanova
// Date: 04/18/2025
// Time: 9:46pm

namespace App\Helpers;

class PriceCalculator
{
    /**
     * Calculate the selling price based on cost and markup.
     */
    public static function calculateSellingPrice(float $costPrice, float $markup, float $markupAmount): float
    {
        if ($markupAmount > 0.0)
            return round($costPrice + $markupAmount, 2);
        if ($markup > 0.0)
            return round($costPrice * $markup, 2);
        else
            return $costPrice;
    }

    public static function calculateDiscountedPrice(float $sellingPrice, float $percentageDiscount, float $discountAmount): float
    {
        if ($percentageDiscount > 0 || $discountAmount > 0) {
            $discountedPrice = $discountAmount > 0
                ? $sellingPrice - $discountAmount
                : $sellingPrice * (1 - $percentageDiscount / 100);

            return round($discountedPrice, 2);
        }

        return 0.0;
    }
}
