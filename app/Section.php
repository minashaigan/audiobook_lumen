<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chapter_number', 'chapter_name', 'time', 'file'
    ];
    /**
     * Get the book that owns the section.
     */
    public function book()
    {
        return $this->belongsTo('App\Book','book_id');
    }
}
