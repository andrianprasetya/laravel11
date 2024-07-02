<?php

namespace App\Traits;

trait Uuid
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model){
            $model->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
    }
}
