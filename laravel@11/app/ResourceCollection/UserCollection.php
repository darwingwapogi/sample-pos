<?php
// Author: DarCasanova
// Date: 03/30/2025
// Time: 7:33pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

class UserCollection {

  public static function filter($collection): array
  {
    $data = [];

    foreach($collection as $item) {
      $data[] = [
        'id' => $item->id,
        'user_name' => $item->username,
        'email' => $item->email,
        'user_type' => $item->user_types->name,
      ];
    }

    return $data;
  }
}