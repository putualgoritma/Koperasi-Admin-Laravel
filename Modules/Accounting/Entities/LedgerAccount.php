<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LedgerAccount extends Model
{
    use HasFactory;

    protected $table = 'ledger_account';

    protected $fillable = [
        'type',
        'amount',
        'ledger_id',
        'account_id',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Accounting\Database\factories\LedgerAccountFactory::new();
    }
}
