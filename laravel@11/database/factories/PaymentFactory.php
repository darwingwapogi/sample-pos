<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'invoice_id' => \App\Models\Invoice::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'status_id' => \App\Models\PaymentStatus::factory(),
            // ...other attributes...
        ];
    }
}
