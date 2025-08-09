<?php
// Author: DarCasanova
// Date: 03/16/2025
// Time: 9:19pm
// Description: used for handling transaction types
// Note: You can add as many enums if needed

namespace App\Enums;

enum TransactionType: int
{
    case SALE = 1; // A regular product purchase.
    case RETURN = 2; // Item returned by the customer.
    case EXCHANGE = 3; // Replacing an item with another.
    case LAYAWAY = 4; // Partial payment for a future purchase.
    case VOID = 5; // Canceled transaction before finalizing.
    case REFUND = 6; // Reversing a completed transaction.
    case DISCOUNTED_SALE = 7; // A sale with a price discount applied.
    case STORE_CREDIT_REDEMPTION = 8; // Using store credit for a purchase.
    case MANUAL_ADJUSTMENT = 9; // Correction of transaction errors.

    public static function getListOfValues(): array
    {
        return [
            self::SALE->value => 'Sale',
            self::RETURN->value => 'Return',
            self::EXCHANGE->value => 'Exchange',
            self::LAYAWAY->value => 'Layaway',
            self::VOID->value => 'Void',
            self::REFUND->value => 'Refund',
            self::DISCOUNTED_SALE->value => 'Discounted Sale',
            self::STORE_CREDIT_REDEMPTION->value => 'Store Credit Redemption',
            self::MANUAL_ADJUSTMENT->value => 'Manual Adjustment',
        ];
    }
}
