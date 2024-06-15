<?php

namespace Database\Seeders;

use App\Models\AddTruck;
use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Calc;
use App\Models\Image;
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
        $trucks = new Truck();

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

        foreach ($trucks->trucks() as $key => $truck) {
            Truck::factory()->create([
                'title' => $truck['title'],
                'desc' => $truck['desc'],
                'price' => $truck['price'],
                'model' => $truck['model'],
                'year_release' => $truck['year_release'],
                'wheel_formula' => $truck['wheel_formula'],
                'engine_power' => $truck['engine_power'],
                'transmission' => $truck['transmission'],
                'fuel' => $truck['fuel'],
                'weight' => $truck['weight'],
                'load_capacity' => $truck['load_capacity'],
                'engine_model' => $truck['engine_model'],
                'wheels' => $truck['wheels'],
                'guarantee' => $truck['guarantee'],
            ]);

            foreach ($truck['images'] as $image) {
                Image::factory()->create([
                    'truck_id' => $key + 1,
                    'name' => $image['name'],
                    'image' => $image['value'],
                ]);
            }

            if (isset($truck['add'])) {
                foreach ($truck['add'] as $add) {
                    AddTruck::factory()->create([
                        'truck_id' => $key + 1,
                        'title' => $add['title'],
                        'value' => $add['value'],
                    ]);
                }
            }
        }
    }
}
