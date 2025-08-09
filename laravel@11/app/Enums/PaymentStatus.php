<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for handling payment status
// Note: You can add as many enums if needed

namespace App\Enums;

enum PaymentStatus: int {
  case Pending = 1;
  case Success = 2;
  case Failed = 3;

  public static function descriptions(): array {
      return [
          self::Pending->name => 'Pending',
          self::Success->name => 'Successful',
          self::Failed->name => 'Failed',
      ];
  }

  public static function values(): array {
      return array_column(self::cases(), 'value');
  }
}
