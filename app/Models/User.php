<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Uuid;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var array<int, string>
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing.
     *
     * @var array<int, string>
     */
    public $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';



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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
