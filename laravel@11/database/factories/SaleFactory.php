<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\Company;
use App\Models\Sale;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\SaleStatus;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'customer_id' => 1,
            'user_id' => 1,
            'company_id' => 1000,
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(array_column(SaleStatus::cases(), 'value')),
            'sold_at' => now()
        ];
    }
}
