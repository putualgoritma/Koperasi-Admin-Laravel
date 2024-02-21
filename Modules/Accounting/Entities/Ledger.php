<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'user_id',
        'register',
        'title',
        'memo',
        'status',
        'login_id',
        'code',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Accounting\Database\factories\LedgerFactory::new();
    }
}
