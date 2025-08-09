<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Sale;

class PaymentSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $saleIds = Sale::pluck('id')->toArray();
        $paymentMethodIds = PaymentMethod::pluck('id')->toArray();
        $statusIds = PaymentStatus::pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            Payment::firstOrCreate([
              'sale_id' => $saleIds[array_rand($saleIds)],
              'method_id' => $paymentMethodIds[array_rand($paymentMethodIds)],
              'status_id' => $statusIds[array_rand($statusIds)],
              'amount' => rand(100, 1000),
              'transaction_id' => Payment::createTransactionId($index),
              'transaction_type_id' => rand(1, 3),
            ]);
        }
    }
}
