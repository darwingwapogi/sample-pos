<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\InvoicePayment;
use App\Models\Invoice;
use App\Models\Payment;

class InvoicePaymentSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $invoiceIds = Invoice::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            InvoicePayment::firstOrCreate([
                'invoice_id' => $invoiceIds[array_rand($invoiceIds)],
                'payment_id' => $paymentIds[array_rand($paymentIds)],
                'amount_paid' => rand(100, 1000)
            ]);
        }
    }
}
