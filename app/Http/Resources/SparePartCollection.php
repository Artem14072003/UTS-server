<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SparePartCollection extends ResourceCollection
{
    public function __construct($resource, $specifications)
    {
        parent::__construct($resource);

        $this->specifications = $specifications;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...array_merge(
                ...$this->collection->map(fn($spare_part) => [
                'id' => $spare_part->id,
                'title' => $spare_part->title,
                'image' => 'http://localhost:8000' . Storage::url($spare_part->image),
                'description' => $spare_part->description,
                'price' => $spare_part->price,
                'model' => $spare_part->model,
                'articul' => $spare_part->articul,
            ])->toArray()),

            'option' => $this->specifications->map(fn($option) => [
                'title' => $option->title,
                'value' => $option->value
            ])->toArray()
        ];
    }
}
