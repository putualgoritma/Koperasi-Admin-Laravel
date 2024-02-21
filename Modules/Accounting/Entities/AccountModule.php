<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountModule extends Model
{
    use HasFactory;

    protected $table = 'account_module';

    protected $fillable = [
        'code',
        'module',
        'account_id',
        'type',
    ];

    protected static function newFactory()
    {
        return \Modules\Accounting\Database\factories\AccountModuleFactory::new ();
    }

    public function scopeFilterInput($query, $request)
    {
        //if code
        if ($request->code != "") {
            $query->where('account_module.code', $request->code)->where('account_module.module', $request->module);
        }
        return $query;
    }
}
