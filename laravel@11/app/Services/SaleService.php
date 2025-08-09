<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for creation of sales
// Note: You can add as many functions if needed

namespace App\Services;

use App\Models\Sale;
use App\Common\Utility\ArrayUtil;
use App\Enums\SaleStatus;

class SaleService extends BaseService {

  public static function createSale($data, bool $isReturnedData) {
    $modelData = [];

    foreach (Sale::$fillableColumn as $column) {
      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    $modelData['status'] = SaleStatus::Pending->value;

    return parent::create($modelData, new Sale, $isReturnedData);
  }

  public static function updateSale($data) {
    $id = ArrayUtil::valueAt($data, 'id');

    $modelData = [];

    foreach (Sale::$fillableColumn as $column) {
      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    return parent::update($modelData, $id, new Sale);
  }

  public static function list($data): array
  {
    $companyId = ArrayUtil::valueAt($data, 'company_id');
    $list = Sale::where('sales.company_id', $companyId)
        ->join('customers', 'sales.customer_id', '=', 'customers.id')
        ->with(['customer:id,lname,fname,mname', 'user:id,username'])
        ->select([
            'sales.id', // Sale ID
            'sales.created_at', // Date & Time
            'sales.sold_at', // Date Sold
            'customer_id', // Customer Name
            'total_amount', // Total Amount
            'status', // Status
            'user_id', // Sales Person
        ])
        ->withCount('saleItems as total_items_sold'); // Added query to count saleItems

    if (isset($data['searchForm'])) {
        foreach ($data['searchForm'] as $field => $value) {
            if ($field === 'customer') {
                $list->whereNameIlike($value);
            }

            else if ($field === 'sold_at' || $field === 'date_created') {
                if ($field === 'date_created') {
                    $field = 'sales.created_at';
                }

                $list->whereDate($field, date('Y-m-d', strtotime($value)));
            }

            else {
                $isWildcard = false;

                if ($field === 'total_amount') {
                    $isWildcard = true;
                }
                
                if ($isWildcard) {
                  $list->whereRaw('LOWER(' . $field . ') LIKE ?', ['%' . strtolower($value) . '%']);
                } else {
                  $list->where($field, $value);
                }
            }
        }
    }

    if (isset($data['sortField'])) {
        // Check if the sortField is in the sortable fields
        // and set the default sort order to 'asc' if not provided
        $sortField = $data['sortField'];
        $sortOrder = $data['sortOrder'] ?? 'ASC';

        $sortableFields = [
            'customer' => ['fname', 'mname', 'lname'],
            'date_created' => 'sales.created_at',
            'date_sold' => 'sold_at',
            'status' => 'status',
            'items_sold' => 'items_sold',
            'amount' => 'total_amount',
        ];

        if (!array_key_exists($sortField, $sortableFields)) {
            // If the sortField is not in the sortable fields, set it to a default value
            $sortField = 'customer';
        }

        if (is_array($sortableFields[$sortField])) {
            foreach ($sortableFields[$sortField] as $field) {
                $list->orderBy($field, $sortOrder);
            }
        } else {
            $list->orderBy($sortableFields[$sortField], $sortOrder);
        }
    }

    return parent::datatableList($data, $list);
  }

  public static function showDetail($id)
  {
    return parent::find($id, new Sale);
  }

  public static function remove($id)
  {
    return parent::delete($id, new Sale);
  }
}