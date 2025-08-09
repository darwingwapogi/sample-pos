<?php
// Author: DarCasanova
// Date: 04/19/2025
// Time: 7:51pm

namespace App\Services;

use App\Models\Supplier;
use App\Common\Utility\ArrayUtil;
use Exception;

class PaymentStatusService extends BaseService{

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
    $supplier = Supplier::where('company_id', $companyId);

    return parent::datatableList($data, $supplier);
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
        return \App\Models\PaymentStatus::whereCompanyOrDefault($companyId)
            ->orderBy('name')
            ->get();
    }
}
