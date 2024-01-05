<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'cover_image' => $this->cover_image ?? '',
            'images' => json_decode($this->images) ?? '',
            'tags' => explode(',', $this->tags) ?? '',
            'start_date' => $this->start_date ?? '',
            'end_date' => $this->end_date ?? '',
            'all_dates' => $this->all_dates ?? '',
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
            'liked_by' => $this->likes->pluck('name')->toArray() ?? [],
        ];
    }
}
