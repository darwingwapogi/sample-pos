<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for creating invoice
// Note: You can add as many functions if needed

namespace App\Services;

use App\Models\Invoice;
use App\Common\Utility\ArrayUtil;
use App\Enums\InvoiceStatus;

class InvoiceService extends BaseService {
  
    public static function createInvoice($data) {
        $modelData = [];

        foreach (Invoice::$fillableColumn as $column) {
            $modelData[$column] = ArrayUtil::valueAt($data, $column);
        }

        $modelData['status'] = InvoiceStatus::Pending->value;

        return Invoice::create($modelData);
    }

    public static function updateInvoice($data) {
        $id = ArrayUtil::valueAt($data, 'id');

        $modelData = [];

        foreach (Invoice::$fillableColumn as $column) {
            $modelData[$column] = ArrayUtil::valueAt($data, $column);
        }

        return parent::update($modelData, $id, new Invoice);
    }

    public static function list($data): array {
        $companyId = ArrayUtil::valueAt($data, 'company_id');
        $item = Invoice::where('company_id', $companyId);

        foreach($data['searchForm'] as $field => $value) {
            if ($field === 'category')
                $data->field = 'category.name';
        }

        if (isset($data['sortField'])) {
            switch($data['sortField']) {
                case 'category';
                    $data['sortField'] = 'category.name';
                break;
            }
        }

        return parent::datatableList($data, $item);
    }

    public static function showDetail($id) {
        return parent::find($id, new Invoice);
    }

    public static function remove($id) {
        return parent::delete($id, new Invoice);
    }
}
