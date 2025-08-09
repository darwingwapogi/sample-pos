<?php
// Author: DarCasanova
// Date: 03/08/2025
// Time: 9:05pm
// Description: Responsible for creating data
// for testing purposes in UAT Server or default data used for developers/clients in Production

namespace Database\Seeders;

use App\Enums\SupplierStatus;
use App\Models\BaseModel;
use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use App\Models\ItemType;
use App\Models\Supplier;
use App\Models\ItemStatus;
use Illuminate\Support\Facades\File;

class ItemSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = Supplier::firstOrCreate(
            ['name' => 'Default Supplier', 'company_id' => BaseModel::$companyId],
            [
              'status' => SupplierStatus::Active,
              'contact_person' => 'John Doe',
              'phone' => '123456789',
              'email' => 'supplier@example.com'
            ]
        );

        $status = ItemStatus::firstOrCreate(['name' => 'In Stock']);
        $items = json_decode(File::get(database_path('data/items.json')), true)['items'];

        foreach ($items as $itemData) {
            $category = Category::firstOrCreate(['name' => $itemData['category']]);
            $unit = Unit::firstOrCreate([
              'name' => $itemData['unit']['name'],
              'symbol' => strtolower($itemData['unit']['symbol'])
            ]);
            $itemType = ItemType::firstOrCreate(['name' => $itemData['item_type']]);

            Item::firstOrCreate([
                'name' => $itemData['name'],
                'brand' => $itemData['brand'],
                'model' => $itemData['model'],
                'description' => $itemData['description'],
                'stock_quantity' => $itemData['stock_quantity'],
                'barcode' => $itemData['barcode'] ?? null,
                'size' => $itemData['size'],
                'color' => $itemData['color'],
                'reorder_level' => $itemData['reorder_level'],
                'cost_price' => $itemData['cost_price'],
                'markup' => $itemData['markup'],
                'markup_amount' => $itemData['markup_amount'],
                'percentage_discount' => $itemData['percentage_discount'],
                'discount_amount' => $itemData['discount_amount'],
                'is_vat_enabled' => $itemData['is_vat_enabled'],
                'category_id' => $category->id,
                'unit_id' => $unit->id,
                'company_id' => BaseModel::$companyId,
                'item_type_id' => $itemType->id,
                'supplier_id' => $supplier->id,
                'status_id' => $status->id,
            ]);
        }
    }
}
