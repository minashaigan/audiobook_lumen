<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;

class Narrator extends Model
{
    use Taggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'introduction', 'image'
    ];
    /**
     * The books that belong to the narrator.
     */
    public function books()
    {
        return $this->belongsToMany('App\Book', 'book_narrator', 'narrator_id', 'book_id');
    }
    /**
     * The genres that belong to the narrator.
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'narrator_genre', 'narrator_id', 'genre_id');
    }
    /**
     * The reviews of the author.
     */
    public function reviews()
    {
        return $this->belongsToMany('App\User', 'user_review_narrator', 'narrator_id', 'user_id')
            ->withPivot('comment','rate','enable');
    }
}
