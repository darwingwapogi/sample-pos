<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'customer_id' => \App\Models\Customer::factory(),
            'total' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            // ...other attributes...
        ];
    }
}
