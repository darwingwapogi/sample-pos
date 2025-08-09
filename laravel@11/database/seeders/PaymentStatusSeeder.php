<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Support\Facades\File;

class PaymentStatusSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Load payment statuses from the JSON file
        $dataPath = database_path('data/payment_statuses.json');
        $statuses = json_decode(File::get($dataPath), true)['payment_statuses'];

        foreach ($statuses as $status) {
            PaymentStatus::firstOrCreate(['name' => $status]);
        }
    }
}
