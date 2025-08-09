<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Support\Facades\File;

class UserTypeSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load user types from the JSON file
        $dataPath = database_path('data/user_types.json');
        $userTypes = json_decode(File::get($dataPath), true)['user_types'];

        foreach ($userTypes as $type) {
            UserType::firstOrCreate(['id' => $type['id']], $type);
        }
    }
}
