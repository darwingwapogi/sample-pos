<?php
// Author: DarCasanova
// Date: 04/03/2025
// Time: 11:07pm
// Description: used for handling supplier statuses
// Note: You can add as many enums if needed

namespace App\Enums;

enum SupplierStatus: string {
  case Active = 'Active';
  case Inactive = 'Inactive';
  case PendingApproval = 'Pending Approval';
  case Blacklisted = 'Blacklisted';
  case Suspended = 'Suspended';
  case Archived = 'Archived';

    public static function getList(): array {
        return array_column(self::cases(), 'value', 'name');
    }
}
