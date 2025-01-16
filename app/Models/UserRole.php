<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use Uuid;

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
        'user_id',
        'role_id',
    ];
}
