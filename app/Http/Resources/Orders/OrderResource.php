<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user' => $this->user ? $this->user->userName() : null,
            'user_id' => $this->user ? $this->user->id : null,
            'attendee_name' => $this->attendee ? $this->attendee->name : null,
            'attendee_email' => $this->attendee ? $this->attendee->email : null,
            'purchase_date' => $this->purchase_date,
            'status' => $this->status,
            'order_details' => OrderDetailResource::collection($this->orderDetails),
        ];
    }
}
