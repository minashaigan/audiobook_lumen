<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'price'
    ];
    /**
     * Get the discounts for the subscription.
     */
    public function discounts()
    {
        return $this->hasMany('App\Discount','subscription_id');
    }
    /**
     * The users that have the subscription.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_has_subscription', 'subscription_id', 'user_id')
            ->withPivot('paid','expiration_date');
    }
}
