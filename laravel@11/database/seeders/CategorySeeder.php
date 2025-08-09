<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Facades\File;

class CategorySeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load categories from the JSON file
        $dataPath = database_path('data/categories.json');
        $categories = json_decode(File::get($dataPath), true)['categories'];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']]);
        }
    }
}
