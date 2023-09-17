<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory, HasUuids;
    
    protected $table = 'refunds';

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

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class);
    }

}
