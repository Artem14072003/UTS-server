<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calc>
 */
class CalcFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'max_lizing' => $this->state('max_lizing'),
            'min_lizing' => $this->state('max_lizing'),
            'percent' =>  $this->state('percent'),
            'term' => $this->state('term')
        ];
    }
}
