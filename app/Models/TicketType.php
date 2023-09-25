<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;
use DB;

class TicketType extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'ticket_types';

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'quantity_available',
        'price',
        'sale_start_date',
        'sale_start_time',
        'sale_end_date',
        'sale_end_time',
        'purchase_limit'
    ];

    public $timestamps = false;

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public function uniqueIds(): array
    {
        return ['id'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function ticketsLimit($eventId)
    {

        $ticketQty = TicketType::where('event_id', $eventId)->count();

        return $ticketQty >= 10 ? true : false;
    }

    public function quantityAvailableLimit($quantity)
    {
        if($this->quantity_sold > $quantity) {
            return true;
        }
        return false;
    }

    public function hasOrders()
    {

        $orders = DB::table('orders')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.ticket_type_id', $this->id)->where('orders.status', '<>', 'Refunded')->count();

        return $orders > 0 ? true : false;

    }
}
