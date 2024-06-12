<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class HomeCollaction extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($cards) {
            return [
                "id" => $cards->id,
                "image" => 'http://localhost:8000' . Storage::url($cards->image),
                "title" => $cards->title,
                "price" => $cards->price,
                "model" => $cards->model,
            ];
        })->toArray();
    }
}
