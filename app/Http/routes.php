<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->group(array('prefix' => 'api/', 'middleware' => [],'namespace' => 'App\Http\Controllers'), function () use ($app) {

    /* Users */
    $app->get('register', 'UserController@register');
    $app->get('login', 'UserController@login');
    $app->get('logout', ['middleware' => 'api', 'uses' => 'UserController@logout']);
// buy
    $app->get('subscriptions/buy/{id}', ['middleware' => 'api', 'uses' => 'UserController@buySubscription']);
    $app->post('subscriptions/verify', ['middleware' => 'api', 'uses' => 'UserController@buySubscriptionVerify']);
//credit
    $app->get('users/credit/{id}', ['middleware' => 'api', 'uses' => 'UserController@increaseCredit']);
    $app->post('users/credit/verify', ['middleware' => 'api', 'uses' => 'UserController@increaseCreditVerify']);
// profile
    $app->get('books/get/{id}', ['middleware' => 'api', 'uses' => 'UserController@getBook']);
    $app->get('users/get/books', ['middleware' => 'api', 'uses' => 'UserController@booksGet']);
    $app->get('users/books', ['middleware' => 'api', 'uses' => 'UserController@booksMayLike']);
    $app->get('books/wish/{id}', ['middleware' => 'api', 'uses' => 'UserController@getWishBook']);
    $app->get('books/wish/delete/{id}', ['middleware' => 'api', 'uses' => 'UserController@deleteWantBook']);

    $app->get('genres/get/{id}', ['middleware' => 'api', 'uses' => 'UserController@getGenre']);
    $app->get('genres/get/delete/{id}', ['middleware' => 'api', 'uses' => 'UserController@deleteGenre']);

    $app->get('password/change', ['middleware' => 'api', 'uses' => 'UserController@changePass']);
    $app->get('image/upload', ['middleware' => 'api', 'uses' => 'UserController@uploadPhoto']);
    $app->get('image/upload/delete', ['middleware' => 'api', 'uses' => 'UserController@deleteUploadPhoto']);
    $app->get('users/delete', ['middleware' => 'api', 'uses' => 'UserController@deleteUser']);
// review
    $app->get('books/review/{id}', ['middleware' => 'api', 'uses' => 'UserController@reviewBook']);
    $app->get('authors/review/{id}', ['middleware' => 'api', 'uses' => 'UserController@reviewAuthor']);
    $app->get('narrators/review/{id}', ['middleware' => 'api', 'uses' => 'UserController@reviewNarrator']);
    /* End Users */

    /* Books */
    $app->get('books', 'BookController@index');
    $app->get('books/{id}', 'BookController@show');
    $app->get('searchBook', 'BookController@search');
    /* End Books */

    /* Authors */
    $app->get('authors', 'AuthorController@index');
    $app->get('authors/{id}', 'AuthorController@show');
    $app->get('searchAuthor', 'AuthorController@search');
    /* End Authors */

    /* Narrators */
    $app->get('narrators', 'NarratorController@index');
    $app->get('narrators/{id}', 'NarratorController@show');
    $app->get('searchNarrator', 'NarratorController@search');
    /* End Narrators */

    /* Subscription */
    $app->get('subscriptions', 'SubscriptionController@index');
    $app->get('subscriptions/{id}', 'SubscriptionController@show');
    /* End Subscription */

});

    
