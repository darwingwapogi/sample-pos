<?php
// Author: DarCasanova
// Date: 04/19/2025
// Time: 7:51pm

namespace App\Services;

use App\Common\Utility\ArrayUtil;
use App\Models\Unit;

class UnitService extends BaseService{

  public static function createOrUpdate($data): array
  {
      $companyId = $data['company_id'];

      if (isset($data['symbol'])) {
        $isDbExists = Unit::
          whereRaw('LOWER(symbol) = ?', [strtolower($data['symbol'])])
          ->whereCompanyOrDefault($companyId)
          ->exists();

        if ($isDbExists) {
            return [
                'success' => false,
                'isDbExists' => true,
                'error' => 'Unit symbol already exists'
            ];
        }
      }

    $id = ArrayUtil::valueAt($data, 'id');

    $modelData = [];

    foreach (Unit::$fillableColumn as $column) {
      if (!isset($data[$column])) continue;

      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    if (isset($id)) {
      return parent::update($modelData, $id, new Unit);
    } else {
      return parent::create($modelData, new Unit);
    }
  }

  public static function list($data): array
  {
    $companyId = ArrayUtil::valueAt($data, 'company_id');
    $item = Unit::where(function($query) use ($companyId) {
      $query->where('company_id', $companyId)
          ->orWhereNull('company_id');
    });

    if (isset($data['searchForm'])) {
      foreach ($data['searchForm'] as $field => $value) {
        $item->whereRaw('LOWER(' . $field . ') LIKE ?', ['%' . strtolower($value) . '%']);
      }
    }

    if (isset($data['sortField'])) {
        // Check if the sortField is in the sortable fields
        // and set the default sort order to 'asc' if not provided
        $sortField = $data['sortField'] ?? 'name';
        $sortOrder = strtoupper($data['sortOrder']);

        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            // If the sortOrder is not valid, set it to 'ASC'
            $sortOrder = 'ASC';
        }

        $sortableFields = [
            'name' => 'name',
            'symbol' => 'symbol'
        ];

        if (!array_key_exists($sortField, $sortableFields)) {
            // If the sortField is not in the sortable fields, set it to a default value
            $sortField = 'name';
        }

        $item->orderBy($sortableFields[$sortField], $sortOrder);
    }

    return parent::datatableList($data, $item);
  }

  public static function showDetail($id)
  {
    return parent::find($id, new Unit);
  }

  public static function remove($id)
  {
    return parent::delete($id, new Unit);
  }

    public static function dbConfigList($companyId)
    {
        return \App\Models\Unit::whereCompanyOrDefault($companyId)
            ->orderBy('name')
            ->get();
    }
}
