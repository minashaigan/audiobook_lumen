<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The books that want by the user.
     */
    public function wishlist()
    {
        return $this->belongsToMany('App\Book', 'user_want_book', 'user_id', 'book_id');
    }
    /**
     * The books that buy by the user.
     */
    public function books()
    {
        return $this->belongsToMany('App\Book', 'user_get_book', 'user_id', 'book_id');
    }
    /**
     * The genres that like by the user.
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'user_genre', 'user_id', 'genre_id');
    }
    /**
     * The subscriptions that buy by the user.
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription', 'user_has_subscription', 'user_id', 'subscription_id')
            ->withPivot('paid','expiration_date');
    }
    /**
     * The books that review by the user.
     */
    public function book_reviews()
    {
        return $this->belongsToMany('App\Book', 'user_review_book', 'user_id', 'book_id')
            ->withPivot('comment','rate');
    }
    /**
     * The authors that review by the user.
     */
    public function author_reviews()
    {
        return $this->belongsToMany('App\Author', 'user_review_author', 'user_id', 'author_id')
            ->withPivot('comment','rate');
    }
    /**
     * The narrators that review by the user.
     */
    public function narrator_reviews()
    {
        return $this->belongsToMany('App\Narrator', 'user_review_narrator', 'user_id', 'narrator_id')
            ->withPivot('comment','rate');
    }
    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
