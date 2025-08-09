<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for handling sale status
// Note: You can add as many enums if needed

namespace App\Enums;

enum SaleStatus: string {
    case Pending = 'Pending';
    case PartiallyPaid = 'Partially Paid';
    case Paid = 'Paid';
    case Cancelled = 'Cancelled';
}
