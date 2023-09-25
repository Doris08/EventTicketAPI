<?php

namespace App\Http\Resources\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TicketType\TicketTypeResource;

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
            'id' => $this->id,
            'organizer' => $this->organizer ? $this->organizer->userName() : null,
            'organizer_id' => $this->organizer ? $this->organizer->id : null,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'image_header_url' => $this->image_header_url,
            'status' => $this->status,
            'tickets' => TicketTypeResource::collection($this->ticketTypes),
        ];
    }
}
