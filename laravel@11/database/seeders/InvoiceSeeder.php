<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\BaseModel;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceStatus;
use App\Models\Sale;

class InvoiceSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $saleIds = Sale::pluck('id')->toArray();
        $customerIds = Customer::pluck('id')->toArray();
        $statusIds = InvoiceStatus::pluck('id')->toArray();

        foreach ($saleIds as $saleId) {
            $isExists = Invoice::where([
                ['company_id', BaseModel::$companyId], 
                ['sale_id', $saleId], 
            ])->exists();

            if ($isExists) {
                continue;
            }

            Invoice::firstOrCreate([
                'sale_id' => $saleId,
                'customer_id' => $customerIds[array_rand($customerIds)],
                'company_id' => BaseModel::$companyId,
                'due_date' => now()->addDays(rand(1, 30)),
                'status_id' => $statusIds[array_rand($statusIds)],
                'total_amount' => rand(100, 1000),
            ]);
        }
    }
}
