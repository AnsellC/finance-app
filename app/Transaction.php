<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'amount',
        'date',
        'type',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'date' => 'datetime',
        'amount' => 'float',
    ];
}
