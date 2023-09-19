<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_id' => $this->order_id,
            'ticket_type_id' => $this->ticket_type_id,
            'ticket_type' => $this->ticketType->name,
            'quantity' => $this->quantity,
            'sale_price' => $this->sale_price,
            'total' => $this->total,
        ];
    }
}
