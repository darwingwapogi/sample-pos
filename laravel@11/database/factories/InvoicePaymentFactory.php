<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicePaymentFactory extends Factory
{
    protected $model = InvoicePayment::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'invoice_id' => \App\Models\Invoice::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'payment_date' => $this->faker->date(),
            // ...other attributes...
        ];
    }
}
