<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\User;
use App\Category;
use Faker\Generator as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 100; $i++) {
            $title = $faker->words(rand(2, 10), true);
            Post::create([
                'title'         => $title,
                'slug'          => Post::generateSlug($title),
                'media'         => 'https://unsplash.it/600/300?image=' . rand(1,200),
                'content'       => $faker->text(rand(200, 1000)),
                'likes'         => $faker->randomNumber(4),
                'comments'      => $faker->randomNumber(4),
                'user_id'       => User::inRandomOrder()->first()->id,
                'category_id'   => Category::inRandomOrder()->first()->id,
            ]);
        };
    }
}
