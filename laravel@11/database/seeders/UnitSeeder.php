<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:17am
// Description: Responsible for creating data
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Support\Facades\File;

class UnitSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Load units from the JSON file
        $dataPath = database_path('data/units.json');
        $unitArray = $this->getAllUnitsAsFlatArray($dataPath);

        foreach ($unitArray as $unit) {
            Unit::firstOrCreate(['name' => $unit['name'], 'symbol' => $unit['symbol']]);
        }
    }

    private function getAllUnitsAsFlatArray(string $dataPath): array
    {
        $units = json_decode(File::get($dataPath), true)['units'];

        $flatUnits = [];

        foreach ($units as $category => $types) {
            if ($category === 'size') {
                foreach ($types as $subCategory => $subCategoryList) {
                    foreach ($subCategoryList as $system => $unitList) {
                        foreach ($unitList as $unit) {
                            $flatUnits[] = [
                                'name' => $unit['name'],
                                'symbol' => $unit['symbol'],
                                'category' => $category,   // length, mass, size
                                'sub_category' => $subCategory,
                                'system' => $system        // metric, imperial
                            ];
                        }
                    }
                }
            } else {
                foreach ($types as $system => $unitList) {
                    foreach ($unitList as $unit) {
                        $flatUnits[] = [
                            'name' => $unit['name'],
                            'symbol' => $unit['symbol'],
                            'category' => $category,   // length, mass, size
                            'system' => $system        // metric, imperial
                        ];
                    }
                }
            }
        }

        return $flatUnits;
    }
}
