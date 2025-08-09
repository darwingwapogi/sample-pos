<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            // ...other attributes...
        ];
    }
}
