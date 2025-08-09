<?php
// Author: DarCasanova
// Date: 04/13/2025
// Time: 09:49pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

use App\Common\Utility\DateUtil;

class ItemTypeCollection {

  public static function filterData($collection): array
  {
    $collection->setCollection($collection->getCollection()->map(function ($item) {
      return [
        'id' => $item->id,
        'name' => $item->name,
        'description' => $item->description
      ];
    }));

    return $collection->toArray();
  }

  public static function transform($item): array
  {
    // Transform the data as needed
    // This is just a placeholder for any transformation logic you might want to apply
    return [
      'id' => $item->id,
      'name' => $item->name,
      'description' => $item->description,
      'created_at' => DateUtil::formatDate($item->created_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
      'updated_at' => DateUtil::formatDate($item->updated_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
    ];
  }
}
