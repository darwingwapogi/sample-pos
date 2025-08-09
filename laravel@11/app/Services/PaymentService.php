<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for payment system
// Note: You can add as many functions if needed

namespace App\Services;

use App\Common\Utility\ArrayUtil;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Payment;
use App\Models\Sale;

class PaymentService {
  public function handlePayment(Sale $sale, array $saleData): Payment
  {
    // 1. Process the payment (this depends on the payment method)
    $modelData = [];

    foreach (Payment::$fillableColumn as $column) {
      $modelData[$column] = ArrayUtil::valueAt($saleData, $column);
    }

    $saleId = $sale['id'];

    $modelData['status_id'] = PaymentStatus::Success->value;
    $modelData['sale_id'] = $saleId;
    $modelData['amount'] = $saleData['amount_paid'];
    $modelData['transaction_id'] = Payment::createTransactionId($saleId);

    $payment = new Payment();
    $payment->fill($modelData)->save();

    // 2. Check if the sale is fully paid
    $totalPaid = Payment::where('sale_id', $saleId)->sum('amount');
    if ($totalPaid >= $sale->total_amount) {
        // Mark the sale as fully paid
        $sale->update([
          'status' => SaleStatus::Paid->value,
          'sold_at' => now(),
        ]);
    } else if ($totalPaid > 0) {
        // Mark the sale as partially paid
        $sale->update(['status' => SaleStatus::PartiallyPaid->value]);
    }

    return $payment;
  }
}
