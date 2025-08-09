<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 8:56am

namespace Database\Factories;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition()
    {
        return [
            // Define the factory attributes here
            'loggable_id' => $this->faker->numberBetween(1, 100),
            'loggable_type' => $this->faker->word,
            'description' => $this->faker->sentence,
            // ...other attributes...
        ];
    }
}
