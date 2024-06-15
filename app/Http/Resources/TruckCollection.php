<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class TruckCollection extends ResourceCollection
{

    public function __construct($resource, $newOption, $swiper, $specifications, $similarTrucks)
    {
        parent::__construct($resource);

        $this->swiper = $swiper;

        $this->newOption = $newOption;

        $this->specifications = $specifications;

        $this->similarTrucks = $similarTrucks;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'swiper' => $this->swiper->map(fn($swipe) => (['image' => 'http://localhost:8000' . Storage::url($swipe['image'])]))->toArray(),
            'cardInfo' => $this->collection->map(function ($truck) {
                return [
                    'title' => $truck->title,
                    'price' => $truck->price,
                    'model' => $truck->model,
                    'desc' => $truck->desc
                ];
            })->toArray(),
            'options' => [
                ...array_map(fn($option) => ([
                    'title' => $option['name'],
                    'value' => $option['value']
                ]), $this->newOption),
            ],
            'add' => [
                ...$this->specifications->map(fn($option) => ([
                    'title' => $option['title'],
                    'value' => $option['value'],
                ]))->toArray()
            ],
            'similar' => $this->similarTrucks->map(function ($card) {
                return [
                    'id' => $card->id,
                    'image' => 'http://localhost:8000' . Storage::url($card->image),
                    'title' => $card->title,
                    'price' => $card->price,
                    'model' => $card->model
                ];
            })->toArray()
        ];
    }
}
