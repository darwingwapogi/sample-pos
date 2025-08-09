<?php
// Author: DarCasanova
// Date: 03/16/2025
// Time: 9:20pm
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients/clients in Production

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TransactionTypeSeeder extends Seeder
{
    public function run()
    {
        // Load transaction types from the JSON file
        $dataPath = database_path('data/transaction_types.json');
        $transactionTypes = json_decode(File::get($dataPath), true)['transaction_types'];

        foreach ($transactionTypes as $type) {
            DB::table('transaction_types')->updateOrInsert(['name' => $type['name']], $type);
        }
    }
}
