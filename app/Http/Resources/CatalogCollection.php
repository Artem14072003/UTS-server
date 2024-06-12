<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class CatalogCollection extends ResourceCollection
{
    public function __construct($resource, $swiper)
    {
        // Вызов родительского конструктора
        parent::__construct($resource);

        // Сохранение дополнительных данных
        $this->swiper = $swiper;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'swiper' => $this->swiper->map(fn($swiper) => ([
                "id" => $swiper->id,
                'image' => 'http://localhost:8000' . Storage::url($swiper->image),
                'title' => $swiper->title
            ]))->toArray(),
            'card' => $this->collection->map(function ($trucks) {
                return [
                    'id' => $trucks->id,
                    'image' => 'http://localhost:8000' . Storage::url($trucks->image),
                    'title' => $trucks->title,
                    'price' => $trucks->price,
                    'model' => $trucks->model
                ];
            })->toArray()
        ];
    }
}
