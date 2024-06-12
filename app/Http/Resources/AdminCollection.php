<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class AdminCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function($user) {
            return [
                "id" => $user->id,
                "login" => $user->login,
                "image" => $user->image === null ? null : Storage::url($user->image),
                "created_at" => $user->created_at,
                "update_at" => $user->update_at,
            ];
        })->toArray();
    }
}
