<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddTruck>
 */
class AddTruckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'truck_id' => $this->state('truck_id'),
            'title' => $this->state('title'),
            'value' => $this->state('value'),
        ];
    }
}
