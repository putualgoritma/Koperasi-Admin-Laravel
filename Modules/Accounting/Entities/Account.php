<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'type',
        'postable',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Accounting\Database\factories\AccountFactory::new();
    }

    public function scopeFilterInput($query, $request)
    {
        //if parent
        if ($request->parent_id != "") {
            $query->where('accounts.id', $request->parent_id);
        }
        return $query;
    }

    public function scopeSetOrderBy($query, $request)
    {
        //if parent
        if (isset($request->order_by)) {
            if(!isset($request->order_by_dir)){
                $order_by_dir = 'ASC';
            }else{
                $order_by_dir = $request->order_by_dir;
            }
            $query->orderBy($request->order_by, $order_by_dir);
        }
        return $query;
    }
}
