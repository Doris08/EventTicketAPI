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
            'user' => $this->user->userName(),
            'user_id' => $this->user->id,
            'purchase_date' => $this->purchase_date,
            'status' => $this->status
        ];
    }
}
