<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Support\Facades\File;

class InvoiceStatusSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load invoice statuses from the JSON file
        $dataPath = database_path('data/invoice_statuses.json');
        $statuses = json_decode(File::get($dataPath), true)['invoice_statuses'];

        foreach ($statuses as $status) {
            InvoiceStatus::firstOrCreate(['name' => $status]);
        }
    }
}
