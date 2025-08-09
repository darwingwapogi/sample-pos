<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data 
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ItemType;
use Illuminate\Support\Facades\File;

class ItemTypeSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('data/item_types.json');
        $itemTypes = json_decode(File::get($filePath), true)['item_types'];

        foreach ($itemTypes as $itemType) {
            ItemType::firstOrCreate($itemType);
        }
    }
}
