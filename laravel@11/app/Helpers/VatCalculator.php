<?php
// Author: DarCasanova
// Date: 03/16/2025
// Time: 10:06pm

namespace App\Helpers;

class VatCalculator
{
    /**
     * Calculate the VAT amount.
     *
     * @param float $amount
     * @param float $vatPercentage
     * @return float
     */
    public static function calculateVatAmount(float $amount, float $vatPercentage): float
    {
        return $amount * ($vatPercentage / 100);
    }

    /**
     * Calculate the total amount including VAT.
     *
     * @param float $amount
     * @param float $vatPercentage
     * @return float
     */
    public static function calculateTotalAmountIncludingVat(float $amount, float $vatPercentage): float
    {
        return $amount + self::calculateVatAmount($amount, $vatPercentage);
    }

    /**
     * Calculate the total amount excluding VAT.
     *
     * @param float $amount
     * @param float $vatPercentage
     * @return float
     */
    public static function calculateTotalAmountExcludingVat(float $amount, float $vatPercentage): float
    {
        return $amount / (1 + ($vatPercentage / 100));
    }

    /**
     * Get VAT details.
     *
     * @param float $amount
     * @param float $vatPercentage
     * @return array
     */
    public static function getVatDetails(float $amount, float $vatPercentage): array
    {
        $vat = self::calculateVatAmount($amount, $vatPercentage);
        $totalSales = self::calculateTotalAmountIncludingVat($amount, $vatPercentage);
        $vatableAmount = self::calculateTotalAmountExcludingVat($amount, $vatPercentage);

        return [
            'vatable_amount' => round($vatableAmount, 2),
            'total_sales' => round($totalSales, 2),
        ];
    }

    /**
     * @param float $sellingPrice
     * @param float $discountedPrice
     * @param bool $isVatEnabled
     * @param float $vatPercentage
     * @return array
     */
    public static function computeFinalSellingPrice(
        float $sellingPrice,
        float $discountedPrice = 0,
        bool $isVatEnabled = false,
        float $vatPercentage = 12
    ): array {
        $price = $discountedPrice > 0 ? $discountedPrice : $sellingPrice;
        if ($isVatEnabled) {
            $vatAmount = $price * ($vatPercentage / 100);
            $finalPrice = $price + $vatAmount;
        } else {
            $vatAmount = 0;
            $finalPrice = $price;
        }

        return [
            'vat_amount' => round($vatAmount, 2),
            'final_selling_price' => round($finalPrice, 2),
        ];
    }

}
