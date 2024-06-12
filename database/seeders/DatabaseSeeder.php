<?php

namespace Database\Seeders;

use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Calc;
use App\Models\Truck;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'login' => 'root',
            'password' => Hash::make('password'),
        ]);

        Calc::factory()->create([
            'max_lizing' => 11000000,
            'min_lizing' => 5000000,
            'percent' => 18,
            'term' => '[12, 24, 36]'
        ]);
    }
}
