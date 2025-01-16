<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    protected $table = "sessions";
    protected $primaryKey = 'id'; // Primary key column
    public $incrementing = false; // If `id` is not auto-incrementing
    protected $keyType = 'string'; // If `id` is a UUID or string
    protected $casts = [
        'last_activity' => 'date', // Ensure last_activity is cast to datetime
    ];

    // Define relationship to user
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
