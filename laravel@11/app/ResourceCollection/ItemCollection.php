<?php
// Author: DarCasanova
// Date: 03/29/2025
// Time: 09:18pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

use App\Common\Utility\DateUtil;
use App\Helpers\NumberHelper;

class ItemCollection {

  public static function filterData($collection): array
  {
    $collection->setCollection($collection->getCollection()->map(function ($item) {
      return self::transform($item);
    }));

    return $collection->toArray();
  }

  public static function transform($item): array
  {
    // Transform the data as needed
    // This is just a placeholder for any transformation logic you might want to apply
    return [
      'id' => $item->id,
      'supplier' => $item->supplier->name,
      'category' => $item->category->name,
      'item_type' => $item->itemType->name,
      'status' => $item->status->name,
      'name' => $item->name,
      'description' => $item->description,
      'brand' => $item->brand,
      'model' => $item->model,
      'sku' => $item->sku,
      'barcode' => $item->barcode,
      'size' => $item->size,
      'color' => $item->color,
      'stock_quantity' => NumberHelper::formatQuantity($item->stock_quantity).$item->unit->symbol,
      'unit' => $item->unit->name,
      'reorder_level' => $item->reorder_level,
      'cost_price' => $item->cost_price,
      'selling_price' => NumberHelper::formatCurrency($item->selling_price),
      'discounted_price' => $item->discounted_price,
      'final_selling_price' => NumberHelper::formatCurrency($item->final_selling_price),
      'whole_sale_price' => $item->whole_sale_price,
      'markup' => $item->markup,
      'markup_amount' => $item->markup_amount,
      'discount_amount' => $item->discount_amount,
      'percentage_discount' => $item->percentage_discount,
      'is_vat_enabled' => $item->is_vat_enabled,
      'vat_amount' => $item->vat_amount,
      'vat_percentage' => $item->vat_percentage,
      'expiry_date' => DateUtil::formatDate($item->expiry_date, DateUtil::FORMAT_CUSTOM_LONG),
      'created_at' => DateUtil::formatDate($item->created_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
      'updated_at' => DateUtil::formatDate($item->updated_at, DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
    ];
  }
}
