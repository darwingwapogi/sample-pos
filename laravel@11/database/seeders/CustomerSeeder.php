<?php

namespace Database\Seeders;

use App\Models\BaseModel;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class CustomerSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load customers from the JSON file
        $customersPath = database_path('data/customers.json');
        $customers = json_decode(File::get($customersPath), true)['customers'];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                [
                'company_id' => BaseModel::$companyId,
                'fname' => $customer['fname'],
                'mname' => $customer['mname'],
                'lname' => $customer['lname'],
              ],
              [
                'address' => $customer['address'],
                'gender' => $customer['gender'],
                'birth_date' => Carbon::parse($customer['birth_date']),
                'contact' => $customer['contact'],
                'encoder' => $customer['encoder']
            ]);
        }
    }
}
