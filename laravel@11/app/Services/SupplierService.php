<?php
// Author: DarCasanova
// Date: 04/19/2025
// Time: 7:51pm

namespace App\Services;

use App\Models\Supplier;
use App\Common\Utility\ArrayUtil;
use Exception;

class SupplierService extends BaseService{

  public static function createOrUpdate($data) {

    $id = ArrayUtil::valueAt($data, 'id');

    $result = [
      'success' => false
    ];

    try {
      if (isset($id)) {
        $update = [
          'name' => $data['name'],
          'contact_person' => $data['contact_person'],
          'email' => $data['email'],
          'phone' => $data['phone'],
          'website' => $data['website'],
          'address' => $data['address'],
          'status' => $data['status'],
        ];

        return parent::update($update, $id, new Supplier);
      } else {
        $create = [
          'company_id' => $data['company_id'],
          'name' => $data['name'],
          'contact_person' => $data['contact_person'],
          'email' => $data['email'],
          'phone' => $data['phone'],
          'website' => $data['website'],
          'address' => $data['address'],
          'status' => $data['status'],
        ];

        return parent::create($create, new Supplier);
      }

      $result['success'] = true;
    } catch (Exception $e) {
      \Log::error('errors: ' . $e->getMessage());
    }

    return $result;
  }

  public static function list(array $data): array
  {
      $companyId = ArrayUtil::valueAt($data, 'company_id');
      $list = Supplier::where('company_id', $companyId);

      /*
        Special case: if the column/s that you are searching is/are from another table.
        You need to specify the exact column name from the table.
        `Table name`.'Column name`
        Ex: category.name
      */
      if (isset($data['searchForm'])) {
          $searchableFields = [
              'name' => ['name', true],
              'contact_person' => ['contact_person', true],
              'status' => ['status', false],
              'address' => ['address', true],
              'location' => ['location', true],
          ];

          foreach($data['searchForm'] as $field => $value) {
              if (isset($searchableFields[$field])) {
                  $column = $searchableFields[$field][0];
                  $isWildcard = $searchableFields[$field][1];

                  if ($isWildcard) {
                      if ($column == 'location') {
                          $list = $list->whereLocation($value);
                      } else {
                          $list->whereRaw('LOWER(' . $column . ') LIKE ?', ['%' . strtolower($value) . '%']);
                      }
                  } else {
                      $list->where($column, $value);
                  }
              }
          }
      }

      if (isset($data['sortField'])) {
          // Check if the sortField is in the sortable fields
          // and set the default sort order to 'asc' if not provided
          $sortField = $data['sortField'];
          $sortOrder = strtoupper($data['sortOrder']);

          if (!in_array($sortOrder, ['ASC', 'DESC'])) {
              // If the sortOrder is not valid, set it to 'ASC'
              $sortOrder = 'ASC';
          }

          $sortableFields = [
              'name' => 'name',
              'contact_person' => 'contact_person',
              'status' => 'status',
              'address' => 'address',
              'location' => ['city', 'province', 'country'],
          ];

          if (!array_key_exists($sortField, $sortableFields)) {
              // If the sortField is not in the sortable fields, set it to a default value
              $sortField = 'name';
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

  public static function detail($id)
  {
    return parent::find($id, new Supplier);
  }

  public static function remove($id)
  {
    return parent::delete($id, new Supplier);
  }

    public static function dbConfigList($companyId)
    {
        return \App\Models\Supplier::where('company_id', $companyId)
            ->orderBy('name')
            ->get();
    }
}
