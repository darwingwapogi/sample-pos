<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class EnumController extends Controller
{
    public function saleStatuslist()
    {
        $saleStatus = \App\Enums\SaleStatus::cases();

        $statuses = array_map(
            fn($status) => ['label' => $status->name, 'value' => $status->value], $saleStatus);
        return response()->json($statuses);
    }

    public function invoiceStatuslist()
    {
        $saleStatus = \App\Enums\InvoiceStatus::cases();

        $statuses = array_map(
            fn($status) => ['label' => $status->name, 'value' => $status->value], $saleStatus);
        return response()->json($statuses);
    }

    public function paymentStatuslist()
    {
        $saleStatus = \App\Enums\PaymentStatus::cases();

        $statuses = array_map(
            fn($status) => ['label' => $status->name, 'value' => $status->value], $saleStatus);
        return response()->json($statuses);
    }

    public function supplierStatuslist()
    {
        $saleStatus = \App\Enums\SupplierStatus::cases();

        $statuses = array_map(
            fn($status) => ['label' => $status->name, 'value' => $status->value], $saleStatus);
        return response()->json($statuses);
    }

    public function transactionTypelist()
    {
        $saleStatus = \App\Enums\TransactionType::cases();

        $statuses = array_map(
            fn($status) => ['label' => $status->name, 'value' => $status->value], $saleStatus);
        return response()->json($statuses);
    }

}
