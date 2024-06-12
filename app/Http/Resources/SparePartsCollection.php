<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SparePartsCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($card) {
            return [
                'id' => $card->id,
                'image' => 'http://localhost:8000' . Storage::url($card->image),
                'title' => $card->title,
                'price' => $card->price,
                'model' => $card->model
            ];
        })->toArray();
    }
}
