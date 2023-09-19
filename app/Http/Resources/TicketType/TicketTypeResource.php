<?php

namespace App\Http\Resources\TicketType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketTypeResource extends JsonResource
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
            'event' => $this->event->name,
            'event_id' => $this->event->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity_available' => $this->quantity_available,
            'price' => $this->price,
            'sale_start_date' => $this->sale_start_date,
            'sale_start_time' => $this->sale_start_time,
            'sale_end_date' => $this->sale_end_date,
            'sale_end_time' => $this->sale_end_time,
            'purchase_limit' => $this->purchase_limit,
            //'tickets' => TicketTypesResource::collection($this->ticketTypes),
        ];
    }
}
