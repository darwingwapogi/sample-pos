<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\Enums\InvoiceStatus;
use App\Models\ItemStatus;
use App\ResourceCollection\DBConfigCollection;
use App\Services\CategoryService;
use App\Services\InvoiceStatusService;
use App\Services\ItemStatusService;
use App\Services\ItemTypeService;
use App\Services\PaymentMethodService;
use App\Services\PaymentStatusService;
use App\Services\SupplierService;
use App\Services\TransactionTypeService;
use App\Services\UnitService;

class DBConfigController extends Controller
{
    public function categoryList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $categories = CategoryService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterCategory($categories);

      return response()->json($configData);
    }

    public function itemTypeList(): \Illuminate\Http\JsonResponse
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $itemTypes = ItemTypeService::dbConfigList($companyId);

        $configData = DBConfigCollection::filterItemTypes($itemTypes);

        return response()->json($configData);
    }

    public function unitList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $units = UnitService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterUnit($units);

      return response()->json($configData);
    }

    public function supplierList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $suppliers = SupplierService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterSupplier($suppliers);

      return response()->json($configData);
    }

    public function paymentMethodList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $paymentMethods = PaymentMethodService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterPaymentMethod($paymentMethods);

      return response()->json($configData);
    }

    public function transactionTypeList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $transactionTypes = TransactionTypeService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterTransactionType($transactionTypes);

      return response()->json($configData);
    }

    public function invoiceStatusList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $invoiceStatuses = InvoiceStatusService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterInvoiceStatus($invoiceStatuses);

      return response()->json($configData);
    }

    public function itemStatusList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $itemStatuses = ItemStatusService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterItemStatus($itemStatuses);

      return response()->json($configData);
    }

    public function paymentStatusList(): \Illuminate\Http\JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $paymentStatuses = PaymentStatusService::dbConfigList($companyId);

      $configData = DBConfigCollection::filterPaymentStatus($paymentStatuses);

      return response()->json($configData);
    }
}
