<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\File;

class PaymentMethodSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Load payment methods from the JSON file
        $dataPath = database_path('data/payment_methods.json');
        $methods = json_decode(File::get($dataPath), true)['payment_methods'];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['name' => $method]);
        }
    }
}
