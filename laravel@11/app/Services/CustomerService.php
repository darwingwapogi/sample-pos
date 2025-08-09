<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 9:28pm
// Description: used for creation of customers
// Note: You can add as many functions if needed

namespace App\Services;

use App\Models\Customer;
use App\Common\Utility\ArrayUtil;

class CustomerService extends BaseService {

  public static function createCustomer($data, bool $isReturnedData = false) {
    $modelData = [];

    foreach (Customer::$fillableColumn as $column) {
      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    return parent::create($modelData, new Customer, $isReturnedData);
  }

  public static function updateCustomer($data) {
    $id = ArrayUtil::valueAt($data, 'id');

    $modelData = [];

    foreach (Customer::$fillableColumn as $column) {
      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    return parent::update($modelData, $id, new Customer);
  }

  public static function list($data): array
  {
    $companyId = ArrayUtil::valueAt($data, 'company_id');
    $list = Customer::where('company_id', $companyId);

    if (isset($data['searchForm'])) {
        foreach ($data['searchForm'] as $field => $value) {
            if ($field === 'full_name') {
                $list->whereNameIlike($value);
            }
            elseif ($field === 'address') {
                $list->whereRaw('LOWER(address) LIKE ?', ['%' . strtolower($value) . '%']);
            }
            else {
                $list->where($field, $value);
            }
        }
    }

    if (isset($data['sortField'])) {
        $sortField = $data['sortField'];
        $sortOrder = $data['sortOrder'] ?? 'ASC';

        $sortableFields = [
            'full_name' => ['fname', 'mname', 'lname'],
            'address' => 'address',
        ];

        if (!array_key_exists($sortField, $sortableFields)) {
            // If the sortField is not in the sortable fields, set it to a default value
            $sortField = 'full_name';
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
    return parent::find($id, new Customer);
  }

  public static function remove($id)
  {
    return parent::delete($id, new Customer);
  }
}
