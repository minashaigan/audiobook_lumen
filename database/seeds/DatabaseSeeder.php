<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call('UserTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('BooksTableSeeder');
        $this->call('SectionsTableSeeder');
        $this->call('AuthorsTableSeeder');
        $this->call('NarratorsTableSeeder');
        $this->call('GenresTableSeeder');
        $this->call('DiscountsTableSeeder');
        $this->call('SubscriptionsTableSeeder');
        $this->call('UserWantBookTableSeeder');
        $this->call('UserGetBookTableSeeder');
        $this->call('UserGenreTableSeeder');
        $this->call('UserHasSubscriptionTableSeeder');
        $this->call('UserReviewBookTableSeeder');
        $this->call('UserReviewAuthorTableSeeder');
        $this->call('UserReviewNarratorTableSeeder');
        $this->call('BookAuthorTableSeeder');
        $this->call('BookNarratorTableSeeder');
        $this->call('BookGenreTableSeeder');
        $this->call('AuthorGenreTableSeeder');
        $this->call('NarratorGenreTableSeeder');
        $this->call('TaggingTaggedTableSeeder');

        Model::reguard();
    }
}
