<?php

namespace Modules\Capital\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capital extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ledger_id',
        'login_id',
        'code',
        'memo',
        'type',
        'amount',
        'register',
        'period',
        'status',
        'model',
    ];

   protected static function newFactory()
    {
        return \Modules\Capital\Database\factories\CapitalFactory::new();
    }
}
