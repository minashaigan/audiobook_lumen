<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'count', 'type', 'value'
    ];
    /**
     * Get the Subscription that owns the discount.
     */
    public function subscription()
    {
        return $this->belongsTo('App\Subscription','subscription_id');
    }
}
