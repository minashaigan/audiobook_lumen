<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Genre;
use App\Tag;
use Illuminate\Http\Request;

/**
 * @resource Author
 *
 *  all functions about authors :
 *      to show all authors , top authors with their information
 *      to show specified author and related information
 */
class AuthorController extends Controller
{
    /**
     * Author List
     *
     * Display a listing of the authors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authors = Author::all();
        $top = [];
        foreach ($authors as $author){
            $author["genres"]="";
            $counter=0;
            foreach ($author->genres()->get() as $genre){
                if($counter)
                    $author["genres"]=$author['genres'].",".$genre->name;
                else
                    $author["genres"]=$genre->name;
                $counter++;
            }
            $rate_count=0;
            $rate_value=0;
            foreach ($author->reviews()->wherePivot('enable',1)->get() as $review){
                if($review->pivot->rate) {
                    $rate_count++;
                    $rate_value += $review->pivot->rate;
                }
            }
            if($rate_count == 0)
                $author['rate']=0;
            else
                $author['rate']=$rate_value/$rate_count;
            if($author['rate']>=4)
                $top[] = $author->name;
        }
        return response()->json(['data'=>['authors'=>$authors,'top_authors'=>$top],'result'=>1,'description'=>'list of authors','message'=>'success']);
    }

    /**
     * Author
     *
     * Display the specified author.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $author = Author::query()->find($id);
        if($author) {
            $author["books"] = $author->books()->get();
            foreach ($author["books"] as $book) {
                $book["authors"] = "";
                $counter = 0;
                foreach ($book->authors()->get() as $bookauthor) {
                    if ($counter)
                        $book["authors"] = $book['authors'] . "," . $bookauthor->name;
                    else
                        $book["authors"] = $bookauthor->name;
                    $counter++;
                }
                $book["narrators"] = "";
                $counter = 0;
                foreach ($book->narrators()->get() as $narrator) {
                    if ($counter)
                        $book["narrators"] = $book['narrators'] . "," . $narrator->name;
                    else
                        $book["narrators"] = $narrator->name;
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
                    $book["rate"] = 0;
                else
                    $book["rate"] = $rate_value / $rate_count;
            }
            $author["genres"] = "";
            $counter = 0;
            foreach ($author->genres()->get() as $genre) {
                if ($counter)
                    $author["genres"] = $author['genres'] . "," . $genre->name;
                else
                    $author["genres"] = $genre->name;
                $counter++;
            }
            $rate_count = 0;
            $rate_value = 0;
            foreach ($author->reviews()->wherePivot('enable', 1)->get() as $review) {
                if ($review->pivot->rate) {
                    $rate_count++;
                    $rate_value += $review->pivot->rate;
                }
            }
            if ($rate_count == 0)
                $author["rate"] = 0;
            else
                $author["rate"] = $rate_value / $rate_count;
            $relating = [];
            foreach ($author->genres()->get() as $genre) {
                $related_book = Author::query()->whereHas('genres', function ($query) use ($genre) {
                    $query->where('genre_id', $genre->id);
                })->distinct()->get();
                if ($related_book)
                    foreach ($related_book as $relate)
                        $relating[] = $relate->name;
            }
            $author["related_author"] = array_diff(array_values(array_unique($relating)), array($author->name));
            $author["reviews"] = $author->reviews()->wherePivot('enable', 1)->get();
            return response()->json(['data' => ['author' => $author], 'result' => 1, 'description' => 'an author', 'message' => 'success']);
        }
        else{
            return response()->json(['data' => [], 'result' => 0, 'description' => 'wrong author id ', 'message' => 'failed']);
        }
    }

    /**
     * Author Search
     *
     * Search the specified author with his related tags, genres, books, name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = [];
        if($request->input('search')) {
            $tags = Tag::query()->where('taggable_type','App\Author')->where('tag_slug', 'like', $request->input('search'))->get();
            if (count($tags)) {
                foreach ($tags as $tag) {
                    $author = Author::query()->findOrFail($tag->taggable_id);
                    $author["genres"] = "";
                    $counter = 0;
                    foreach ($author->genres()->get() as $genre) {
                        if ($counter)
                            $author["genres"] = $author['genres'] . "," . $genre->name;
                        else
                            $author["genres"] = $genre->name;
                        $counter++;
                    }
                    $rate_count = 0;
                    $rate_value = 0;
                    foreach ($author->reviews()->wherePivot('enable', 1)->get() as $review) {
                        if ($review->pivot->rate) {
                            $rate_count++;
                            $rate_value += $review->pivot->rate;
                        }
                    }
                    if ($rate_count == 0)
                        $author['rate'] = 0;
                    else
                        $author['rate'] = $rate_value / $rate_count;
                
                    $search[] = $author;
                }
            }
            $genres = Genre::query()->where('name', 'like', $request->input('search'))->get();
            if (count($genres)) {
                foreach ($genres as $genre) {
                    $authors = Author::query()->whereHas('genres', function ($query) use ($genre) {
                        $query->where('genre_id', $genre->id);
                    })->distinct()->get();
                    foreach ($authors as $author) {
                        $author["genres"] = "";
                        $counter = 0;
                        foreach ($author->genres()->get() as $genre) {
                            if ($counter)
                                $author["genres"] = $author['genres'] . "," . $genre->name;
                            else
                                $author["genres"] = $genre->name;
                            $counter++;
                        }
                        $rate_count = 0;
                        $rate_value = 0;
                        foreach ($author->reviews()->wherePivot('enable', 1)->get() as $review) {
                            if ($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if ($rate_count == 0)
                            $author['rate'] = 0;
                        else
                            $author['rate'] = $rate_value / $rate_count;
                    }
                    foreach ($authors as $author)
                        $search[] = $author;
                }
            }
            $authors = Author::query()->where('name', 'like', $request->input('search'))->get();
            if (count($authors)) {
                foreach ($authors as $author) {
                    $author["genres"] = "";
                    $counter = 0;
                    foreach ($author->genres()->get() as $genre) {
                        if ($counter)
                            $author["genres"] = $author['genres'] . "," . $genre->name;
                        else
                            $author["genres"] = $genre->name;
                        $counter++;
                    }
                    $rate_count = 0;
                    $rate_value = 0;
                    foreach ($author->reviews()->wherePivot('enable', 1)->get() as $review) {
                        if ($review->pivot->rate) {
                            $rate_count++;
                            $rate_value += $review->pivot->rate;
                        }
                    }
                    if ($rate_count == 0)
                        $author['rate'] = 0;
                    else
                        $author['rate'] = $rate_value / $rate_count;

                    $search[] = $author;
                }
            }
            $books = Book::query()->where('name', 'like', $request->input('search'))->get();
            if (count($books)) {
                foreach ($books as $book) {
                    $authors = Author::query()->whereHas('books', function ($query) use ($book) {
                        $query->where('book_id', $book->id);
                    })->distinct()->get();
                    foreach ($authors as $author) {
                        $author["genres"] = "";
                        $counter = 0;
                        foreach ($author->genres()->get() as $genre) {
                            if ($counter)
                                $author["genres"] = $author['genres'] . "," . $genre->name;
                            else
                                $author["genres"] = $genre->name;
                            $counter++;
                        }
                        $rate_count = 0;
                        $rate_value = 0;
                        foreach ($author->reviews()->wherePivot('enable', 1)->get() as $review) {
                            if ($review->pivot->rate) {
                                $rate_count++;
                                $rate_value += $review->pivot->rate;
                            }
                        }
                        if ($rate_count == 0)
                            $author['rate'] = 0;
                        else
                            $author['rate'] = $rate_value / $rate_count;
                    }
                    foreach ($authors as $author)
                        $search[] = $author;
                }
            }
            if(!count($tags) and !count($authors) and !count($books) and !count($genres)){
                return response()->json(['data' => [], 'result' => 0, 'description' => 'there is no such an item', 'message' => 'موردی یافت نشد']);
            }
        }
        return response()->json(['data'=>['search_result'=>array_unique($search)],'result'=>1,'description'=>'list of authors by searching','message'=>'success']);
    }
}
