<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];
    /**
     * The users that belong to the genre.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_genre', 'genre_id', 'user_id');
    }
    /**
     * The books that belong to the genre.
     */
    public function books()
    {
        return $this->belongsToMany('App\Book', 'book_genre', 'genre_id', 'book_id');
    }
    /**
     * The authors that belong to the genre.
     */
    public function authors()
    {
        return $this->belongsToMany('App\Author', 'author_genre', 'genre_id', 'author_id');
    }
    /**
     * The narrators that belong to the genre.
     */
    public function narrators()
    {
        return $this->belongsToMany('App\Narrator', 'narrator_genre', 'genre_id', 'narrator_id');
    }
}
