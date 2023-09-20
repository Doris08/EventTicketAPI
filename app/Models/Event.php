<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class Event extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'events';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public function uniqueIds(): array
    {
        return ['id'];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasTickets(){
        return $this->ticketTypes()->exists();
    }

    public function hasOrders(){
        if($this->orders()->exists()){
            $orders = Order::where('event_id', $this->id)->where('status', '<>', 'Refunded')->count();
            if($orders > 0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
