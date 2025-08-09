<?php
// Author: DarCasanova
// Date: 03/08/2025
// Time: 4:43pm
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\BaseModel;
use App\Models\Company;
use Illuminate\Support\Facades\File;

class CompanySeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::firstOrCreate([
          'id' => BaseModel::$companyId,
          'name' => 'DDR3 Team',
          'abbr' => 'DDR3',
          'email' => 'ddr3@company.com',
          'address_line1' => 'Tulunan, North Cotabato, Philippines'
        ]);

        // Load companies from the JSON file
        $dataPath = database_path('data/companies.json');
        $companies = json_decode(File::get($dataPath), true)['companies'];

        foreach ($companies as $company) {
            Company::firstOrCreate([
                'id' => $company['id'],
                'name' => $company['name'],
                'abbr' => $company['abbr'],
                'email' => $company['email'],
                'address_line1' => $company['address_line1']
            ]);
        }
    }
}
