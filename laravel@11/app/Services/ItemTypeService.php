<?php

namespace App\Services;

use App\Models\ItemType;
use App\Common\Utility\ArrayUtil;

class ItemTypeService extends BaseService {

  public static function createOrUpdate($data): array
  {
    $companyId = $data['company_id'];

    if (isset($data['name'])) {
        $isDbExists = ItemType::where('name', $data['name'])
            ->whereCompanyOrDefault($companyId)
            ->exists();
    
        if ($isDbExists) {
          return [
              'success' => false,
              'isDbExists' => true,
              'error' => 'Item Type already exists'
          ];
        }
    }

    $id = ArrayUtil::valueAt($data, 'id');

    $modelData = [];

    foreach (ItemType::$fillableColumn as $column) {
      if (!isset($data[$column])) continue;
      
      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    if (isset($id)) {
      return parent::update($modelData, $id, new ItemType);
    } else {
      return parent::create($modelData, new ItemType);
    }
  }

  public static function list($data): array
  {
    $companyId = ArrayUtil::valueAt($data, 'company_id');
    $list = ItemType::whereCompanyOrDefault($companyId);

    /*
      Special case: if the column/s your are searching is/are from another table.
      You need to specify the exact column name from the table.
      `Table name`.'Column name`
      Ex: category.name
    */
    if (isset($data['searchForm'])) {
      $searchableFields = [
        'name' => ['name', true],
        'description' => ['description', true]
      ];

      foreach($data['searchForm'] as $field => $value) {
        if (isset($searchableFields[$field])) {
          $column = $searchableFields[$field][0];
          $isWildcard = $searchableFields[$field][1];

          if ($isWildcard) {
            $list->whereRaw('LOWER(' . $column . ') LIKE ?', ['%' . strtolower($value) . '%']);
          } else {
            $list->where($column, $value);
          }
        }
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
            'description' => 'description'
        ];

        if (!array_key_exists($sortField, $sortableFields)) {
            // If the sortField is not in the sortable fields, set it to a default value
            $sortField = 'name';
        }

        $list->orderBy($sortableFields[$sortField], $sortOrder);
    }

    return parent::datatableList($data, $list);
  }

  public static function showDetail($id)
  {
    return parent::find($id, new ItemType);
  }

  public static function remove($id)
  {
    return parent::delete($id, new ItemType);
  }

  public static function dbConfigList($companyId)
  {
      return \App\Models\ItemType::whereCompanyOrDefault($companyId)
          ->orderBy('name')
          ->get();
  }
}
