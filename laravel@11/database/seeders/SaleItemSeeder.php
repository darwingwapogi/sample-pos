<?php
// Author: DarCasanova
// Date: 03/30/2025
// Time: 6:01pm
// Description: Responsible for creating sale item data
// and associating them with sales for testing or default purposes.

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item;
use Faker\Factory as Faker;

class SaleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        Sale::all()->each(function ($sale) use ($faker) {
            $items = Item::inRandomOrder()->take(5)->get(); // Get up to 5 random unique items

            $itemCount = $faker->numberBetween(1, $items->count());

            $items->take($itemCount)->each(function ($item) use ($sale, $faker) {
                $quantity = $faker->numberBetween(1, 10);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'cost_price' => $item->cost_price,
                    'markup' => $item->markup,
                    'markup_amount' => $item->markup_amount,
                    'percentage_discount' => $item->percentage_discount,
                    'discount_amount' => $item->discount_amount,
                    'whole_sale_price' => $item->whole_sale_price,
                    'is_vat_enabled' => $item->is_vat_enabled,
                ]);
            });
        });
    }
}
