<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Attendee extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'attendees';

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
