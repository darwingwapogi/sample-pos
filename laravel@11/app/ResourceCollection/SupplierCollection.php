<?php
// Author: DarCasanova
// Date: 03/30/2025
// Time: 7:33pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

use App\Common\Utility\DateUtil;
use App\Common\Utility\AddressUtil;

class SupplierCollection {

  public static function filterData($collection)
  {
    $collection->setCollection($collection->getCollection()->map(function ($supplier) {
      return self::transform($supplier);
    }));

    return $collection->toArray();
  }

  public static function transform($item): array
  {
    return [
      'id' => $item->id,
      'name' => $item->name,
      'contact_person' => $item->contact_person,
      'status' => $item->status,
      'email' => $item->email,
      'phone' => $item->phone,
      'website' => $item->website,
      'address' => $item->address,
      'city' => $item->city,
      'province' => $item->province,
      'country' => $item->country,
      'zip_code' => $item->zip_code,
      'location' => AddressUtil::formatAddress($item->city, $item->province, $item->country),
      'created_at' => DateUtil::formatDate($item->created_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
      'updated_at' => DateUtil::formatDate($item->updated_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
    ];
  }
}
