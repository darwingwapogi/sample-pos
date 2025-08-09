<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load users from the JSON file
        $dataPath = database_path('data/users.json');
        $users = json_decode(File::get($dataPath), true)['users'];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                [
                    'email' => $user['email'],
                    'email_verified_at' => now(),
                    'password' => \Hash::make($user['password']),
                    'user_type_id' => $user['user_type_id'],
                    'company_id' => BaseModel::$companyId,
                    'isActive'  => true
                ]
            );
        }
    }
}
