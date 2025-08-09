<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentStatusFactory extends Factory
{
    protected $model = PaymentStatus::class;

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
