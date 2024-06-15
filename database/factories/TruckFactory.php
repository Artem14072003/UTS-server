<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Truck>
 */
class TruckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                'title' => $this->state('title'),
                'desc' => $this->state('desc'),
                'price' => $this->state('price'),
                'model' => $this->state('model'),
                'year_release' => $this->state('year_release'),
                'wheel_formula' => $this->state('wheel_formula'),
                'engine_power' => $this->state('engine_power'),
                'transmission' => $this->state('transmission'),
                'fuel' => $this->state('fuel'),
                'weight' => $this->state('weight'),
                'load_capacity' => $this->state('load_capacity'),
                'engine_model' => $this->state('engine_model'),
                'wheels' => $this->state('wheels'),
                'guarantee' => $this->state('guarantee'),
        ];
    }
}
