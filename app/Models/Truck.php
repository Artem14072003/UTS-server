<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected array $renameOprion = [
        "year_release" => 'Год выпуска',
        "wheel_formula" => 'Колесная формула',
        "engine_power" => 'Мощность двигателя',
        "transmission" => 'Коробка передач',
        "fuel" => 'Топливный бак 1 (л.)',
        "weight" => 'Допустимая полная масса (кг.)',
        "load_capacity" => 'Грузоподъемность ',
        "engine_model" => 'Модель двигателя',
        "wheels" => 'Колеса',
        "guarantee" => 'Гарантия',
    ];

    public function setNewOption($options): array
    {
        $newOption = [];

        foreach ($options as $index => $option) {
            foreach ($option->original as $key => $item) {
                $newOption[] = [
                    'name' => $this->renameOprion[$key],
                    'value' => $item
                ];
            }
        }

        return $newOption;
    }
}
