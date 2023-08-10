<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * The following that belong to the Feed
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connectionRequestAsSender(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'sender_id', 'id');
    }

    /**
     * The following that belong to the Feed
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connectionRequestAsReceiver(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'receiver_id', 'id');
    }

}
