<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'card_id',
        'birthday',
        'sex',
        'email',
        'phone',
        'phone2',
        'description',
    ];

    public function contactAddress( )
    {
        return $this->hasMany(ContactAddress::class, 'contact_id')->select('*')->with('subdistrict');
    }

    public function scopeFilterInput($query, $request)
    {
        //if keyword
        if ($request->keyword != "") {
            $keyword = $request->keyword;
            $query->where(function ($qry) use ($keyword) {
                $qry->where('contacts.name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('contacts.email', 'LIKE', '%'.$keyword.'%');
            });
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
            $query->orderBy('contacts.'.$request->order_by, $order_by_dir);
        }
        return $query;
    }
}
