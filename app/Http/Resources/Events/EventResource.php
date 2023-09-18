<?php

namespace App\Http\Resources\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'organizer' => $this->organizer->organizerName,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'image_header_url' => $this->image_header_url,
            'status' => $this->status,
            //'posts' => PostResource::collection($this->posts),
        ];
    }
}
