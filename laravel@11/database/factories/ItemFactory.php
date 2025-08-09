<?php

namespace Database\Factories;

use App\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'description' => $this->faker->sentence,
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'reorder_level' => $this->faker->numberBetween(1, 10),
            'cost_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 200),
            'category_id' => \App\Models\Category::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'company_id' => \App\Models\Company::factory(),
            'item_type_id' => \App\Models\ItemType::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'status_id' => \App\Models\ItemStatus::factory(),
        ];
    }
}