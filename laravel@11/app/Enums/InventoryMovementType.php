<?php
// Author: DarCasanova
// Date: 04/17/2025
// Time: 8:12pm
// Description: used for handling inventory movement type
// Note: You can add as many enums if needed

namespace App\Enums;

enum InventoryMovementType: string
{
    case STOCK_IN = 'IN';//add stock
    case STOCK_OUT = 'OUT';//deduct stock
    case ADJUSTMENT_INCREASE = 'ADJ_INCREASE';//add stock
    case ADJUSTMENT_DECREASE = 'ADJ_DECREASE';//deduct stock
    case RETURN = 'RET';//deduct stock
    case TRANSFER = 'TRF';//deduct stock
    case EXPIRED = 'EXP';//deduct stock
    case DAMAGED = 'DAM';//deduct stock
    case COUNT = 'CNT';

    public function label(): string
    {
        return match ($this) {
            self::STOCK_IN => 'Stock In',
            self::STOCK_OUT => 'Stock Out',
            self::ADJUSTMENT_INCREASE => 'Adjustment (Increase)',
            self::ADJUSTMENT_DECREASE => 'Adjustment (Decrease)',
            self::RETURN => 'Customer Return',
            self::TRANSFER => 'Warehouse Transfer',
            self::EXPIRED => 'Expired Item',
            self::DAMAGED => 'Damaged Item',
            self::COUNT => 'Inventory Count',
        };
    }

    // Optional: Useful for dropdowns or UI
    public static function toArray(): array
    {
        return array_map(fn($type) => [
            'value' => $type->value,
            'label' => $type->label()
        ], self::cases());
    }
}
