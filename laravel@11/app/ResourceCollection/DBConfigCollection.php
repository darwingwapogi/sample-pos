<?php
// Author: DarCasanova
// Date: 04/09/2025
// Time: 09:47pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

class DBConfigCollection {

  public static function filterCategory($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

    public static function filterItemTypes($collection): array
    {
        $filteredCollection = $collection->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->id,
            ];
        });

        return $filteredCollection->toArray();
    }

  public static function filterUnit($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterPaymentMethod($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterSupplier($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterTransactionType($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterInvoiceStatus($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterItemStatus($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }

  public static function filterPaymentStatus($collection): array
  {
    $filteredCollection = $collection->map(function ($item) {
      return [
        'label' => $item->name,
        'value' => $item->id,
      ];
    });

    return $filteredCollection->toArray();
  }
}
