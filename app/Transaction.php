<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'transid', 'condition', 'type'
    ];
    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
