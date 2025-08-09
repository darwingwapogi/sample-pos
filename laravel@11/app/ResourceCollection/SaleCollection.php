<?php
// Author: DarCasanova
// Date: 03/30/2025
// Time: 7:33pm
// Description: Provides methods for transforming and filtering data
  // for consistent output of API responses or other use cases.
// Note: You can add as many functions as you want if needed

namespace App\ResourceCollection;

use App\Common\Utility\DateUtil;
use App\Common\Utility\NameUtil;

class SaleCollection
{
    public static function filterData($collection)
    {
      $collection->setCollection($collection->getCollection()->map(function ($sale) {
        return [
            'id' => $sale->id,
            'date_created' => DateUtil::formatDate($sale->created_at, 
                DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
            'customer' => NameUtil::getLastNameFirst($sale->customer->fname, 
                $sale->customer->lname, $sale->customer->mname),
            'items_sold' => $sale->total_items_sold . ' items', // Count of items
            'amount' => number_format($sale->total_amount, 2),
            'status' => $sale->status,
            'date_sold' => DateUtil::formatDate($sale->sold_at, 
                DateUtil::FORMAT_DAY_DATE_TIME_12_HOUR),
          ];
      }));
        
      return $collection->toArray();
    }
}
