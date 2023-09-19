<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'password',
    ];

    protected $maps = [
        'first_name' => 'firstName',
        'last_name' => 'lastName',
        'company_name' => 'companyName',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }
    
    public function uniqueIds(): array
    {
        return ['id'];
    }

    public function attendee(): HasOne
    {
        return $this->hasOne(UserType::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function userName()
    {
        return $this->first_name." ".$this->last_name;
    }
}
