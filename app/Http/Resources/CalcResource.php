<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CalcResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($calc) {
            return [
                'minlizing' => $calc->min_lizing,
                'maxlizing' => $calc->max_lizing,
                'percent' => $calc->percent,
                'term' => json_decode($calc->term, true)
            ];
        })->toArray();
    }
}
