<?php

namespace App\Http\Controllers;

use App\Narrator;
use App\Book;
use App\Genre;
use App\Tag;
use Illuminate\Http\Request;

/**
 * @resource Narrator
 *
 *  all functions about narrators :
 *      to show all narrators , top narrators with their information
 *      to show specified narrator and related information
 *
 * Class NarratorController
 * @package App\Http\Controllers
 */
class NarratorController extends Controller
{
    /**
     * Narrator List
     *
     * Display a listing of the narrators.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $narrators = Narrator::all();
        $top = [];
        foreach ($narrators as $narrator){
            $narrator["genres"]="";
            $counter=0;
            foreach ($narrator->genres()->get() as $genre){
                if($counter)
                    $narrator["genres"]=$narrator['genres'].",".$genre->name;
                else
                    $narrator["genres"]=$genre->name;
                $counter++;
            }
            $rate_count=0;
            $rate_value=0;
            foreach ($narrator->reviews()->wherePivot('enable',1)->get() as $review){
                if($review->pivot->rate) {
                    $rate_count++;
                    $rate_value += $review->pivot->rate;
                }
            }
            if($rate_count == 0)
                $narrator['rate']=0;
            else
                $narrator['rate']=$rate_value/$rate_count;
            if($narrator['rate']>=4)
                $top[] = $narrator->name;
        }
        return response()->json(['data'=>['narrators'=>$narrators,'top_narrators'=>$top],'result'=>1,'description'=>'list of narrators','message'=>'success']);
    }

    /**
     * Narrator
     *
     * Display the specified narrator.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $narrator = Narrator::query()->find($id);
        if($narrator) {
            $narrator["books"] = $narrator->books()->get();
            foreach ($narrator["books"] as $book) {
                $book["authors"] = "";
                $counter = 0;
                foreach ($book->authors()->get() as $author) {
                    if ($counter)
                        $book["authors"] = $book['authors'] . "," . $author->name;
                    else
                        $book["authors"] = $author->name;
                    $counter++;
                }
                $book["narrators"] = "";
                $counter = 0;
                foreach ($book->narrators()->get() as $booknarrator) {
                    if ($counter)
                        $book["narrators"] = $book['narrators'] . "," . $booknarrator->name;
                    else
                        $book["narrators"] = $booknarrator->name;
                    $counter++;
                }
                $book["genres"] = "";
                $counter = 0;
                foreach ($book->genres()->get() as $genre) {
                    if ($counter)
                        $book["genres"] = $book['genres'] . "," . $genre->name;
                    else
                        $book["genres"] = $genre->name;
                    $counter++;
                }
                $rate_count = 0;
                $rate_value = 0;
                foreach ($book->reviews()->wherePivot('enable', 1)->get() as $review) {
                    if ($review->pivot->rate) {
                        $rate_count++;
                        $rate_value += $review->pivot->rate;
                    }
                }
                if ($rate_count == 0)
                    $book['rate'] = 0;
                else
                    $book['rate'] = $rate_value / $rate_count;
            }
            $narrator["genres"] = "";
            $counter = 0;
            foreach ($narrator->genres()->get() as $genre) {
                if ($counter)
                    $narrator["genres"] = $narrator['genres'] . "," . $genre->name;
                else
                    $narrator["genres"] = $genre->name;
                $counter++;
            }
            $rate_count = 0;
            $rate_value = 0;
            foreach ($narrator->reviews()->wherePivot('enable', 1)->get() as $review) {
                if ($review->pivot->rate) {
                    $rate_count++;
                    $rate_value += $review->pivot->rate;
                }
            }
            if ($rate_count == 0)
                $narrator['rate'] = 0;
            else
                $narrator['rate'] = $rate_value / $rate_count;
            $relating = [];
            foreach ($narrator->genres()->get() as $genre) {
                $related_book = Narrator::query()->whereHas('genres', function ($query) use ($genre) {
                    $query->where('genre_id', $genre->id);
                })->distinct()->get();
                if ($related_book)
                    foreach ($related_book as $relate)
                        $relating[] = $relate->name;
            }
            $narrator['related_narrator'] = array_diff(array_values(array_unique($relating)), array($narrator->name));
            $narrator['reviews'] = $narrator->reviews()->wherePivot('enable', 1)->get();
            return response()->json(['data' => ['narrator' => $narrator], 'result' => 1, 'description' => 'a narrator', 'message' => 'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong narrator id ', 'message' => 'failed']);
        }
    }

    /**
     * Narrator Search
     *
     * Search the specified narrator with his related genres, books, tags, name.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = [];
        if($request->input('search')) {
            $tags = Tag::query()->where('taggable_type','App\Narrator')->where('tag_slug', 'like', $request->input('search'))->get();
            if (count($tags)) {
                foreach ($tags as $tag) {
                    $narrator = Narrator::query()->find($tag->taggable_id);
                    if($narrator) {
                        $narrator["genres"] = "";
                        $counter = 0;
                        foreach ($narrator->genres()->get() as $genre) {
                            if ($counter)
                                $narrator["genres"] = $narrator['genres'] . "," . $genre->name;
                            else
                                $narrator["genres"] = $genre->name;
                            $counter++;
                        }
                        $rate_count = 0;
                        $rate_value = 0;
                        foreach ($narrator->reviews()->wherePivot('enable', 1)->get() as $review) {
                            if ($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if ($rate_count == 0)
                            $narrator['rate'] = 0;
                        else
                            $narrator['rate'] = $rate_value / $rate_count;
                        $search[] = $narrator;
                    }
                }
            }
            $genres = Genre::query()->where('name', 'like', $request->input('search'))->get();
            if (count($genres)) {
                foreach ($genres as $genre) {
                    $narrators = Narrator::query()->whereHas('genres', function ($query) use ($genre) {
                        $query->where('genre_id', $genre->id);
                    })->distinct()->get();
                    foreach ($narrators as $narrator) {
                        $narrator["genres"] = "";
                        $counter = 0;
                        foreach ($narrator->genres()->get() as $genre) {
                            if ($counter)
                                $narrator["genres"] = $narrator['genres'] . "," . $genre->name;
                            else
                                $narrator["genres"] = $genre->name;
                            $counter++;
                        }
                        $rate_count = 0;
                        $rate_value = 0;
                        foreach ($narrator->reviews()->wherePivot('enable', 1)->get() as $review) {
                            if ($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if ($rate_count == 0)
                            $narrator['rate'] = 0;
                        else
                            $narrator['rate'] = $rate_value / $rate_count;
                    }
                    foreach ($narrators as $narrator)
                        $search[] = $narrator;
                }
            }
            $narrators = Narrator::query()->where('name', 'like', $request->input('search'))->get();
            if (count($narrators)) {
                foreach ($narrators as $narrator) {
                    $narrator["genres"] = "";
                    $counter = 0;
                    foreach ($narrator->genres()->get() as $genre) {
                        if ($counter)
                            $narrator["genres"] = $narrator['genres'] . "," . $genre->name;
                        else
                            $narrator["genres"] = $genre->name;
                        $counter++;
                    }
                    $rate_count = 0;
                    $rate_value = 0;
                    foreach ($narrator->reviews()->wherePivot('enable', 1)->get() as $review) {
                        if ($review->pivot->rate) {
                            $rate_count++;
                            $rate_value += $review->pivot->rate;
                        }
                    }
                    if ($rate_count == 0)
                        $narrator['rate'] = 0;
                    else
                        $narrator['rate'] = $rate_value / $rate_count;

                    $search[] = $narrator;
                }
            }
            $books = Book::query()->where('name', 'like', $request->input('search'))->get();
            if (count($books)) {
                foreach ($books as $book) {
                    $narrators = Narrator::query()->whereHas('books', function ($query) use ($book) {
                        $query->where('book_id', $book->id);
                    })->distinct()->get();
                    foreach ($narrators as $narrator) {
                        $narrator["genres"] = "";
                        $counter = 0;
                        foreach ($narrator->genres()->get() as $genre) {
                            if ($counter)
                                $narrator["genres"] = $narrator['genres'] . "," . $genre->name;
                            else
                                $narrator["genres"] = $genre->name;
                            $counter++;
                        }
                        $rate_count = 0;
                        $rate_value = 0;
                        foreach ($narrator->reviews()->wherePivot('enable', 1)->get() as $review) {
                            if ($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if ($rate_count == 0)
                            $narrator['rate'] = 0;
                        else
                            $narrator['rate'] = $rate_value / $rate_count;
                    }
                    foreach ($narrators as $narrator)
                        $search[] = $narrator;
                }
            }
            if(!count($tags) and !count($narrators) and !count($books) and !count($genres)){
                return response()->json(['data' => [], 'result' => 0, 'description' => 'there is no such an item ', 'message' => 'موردی یافت نشد']);
            }
        }
        return response()->json(['data'=>['search_result'=>array_unique($search)],'result'=>1,'description'=>'list of narrators by searching','message'=>'success']);
    }
}
