<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 9:48pm
// Description: used for handling invoice status
// Note: You can add as many enums if needed

namespace App\Enums;

enum InvoiceStatus: string {
    case Draft = 'Draft';
    case Pending = 'Pending';
    case Sent = 'Sent';
    case Viewed = 'Viewed';
    case PartiallyPaid = 'Partially Paid';
    case Paid = 'Paid';
    case Overdue = 'Overdue';
    case Canceled = 'Canceled';
    case Refunded = 'Refunded';
    case Failed = 'Failed';
    case Disputed = 'Disputed';
    case WrittenOff = 'Written Off';

    public static function getList(): array {
        return array_column(self::cases(), 'name');
    }

    public static function getIndex(string $status): int {
        $statuses = array_column(self::cases(), 'name');
        return array_search($status, $statuses);
    }

    public static function getFormattedList(): array {
        return array_column(self::cases(), 'value');
    }
}
