<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Movie;
use Goutte\Client;
use Illuminate\Database\Seeder;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $url = "http://uf.tabfil.me/";

        $client = new Client(HttpClient::create(['timeout' => 60]));
        $crawler = $client->request('GET', $url);

        // Get the movie links array
        $movie_links = $crawler->filter('#dle-content > .mov')->each(function (Crawler $node) {
            return $node->filter('a.mov-t')->attr('href');
        });
        $categories = [];
        // если там уже была добавлена категория
        $category_id = Category::latest()->first()->id ?? 1;
        foreach ($movie_links as $link) {
            $request = $client->request('GET', $link);
            // проверяю по жанру и добавляю это в ответ cats
            $cats = '';
            $request->filter('.mov-list')->children()->each(function (Crawler $node) use (&$cats) {
                $cat = $node->filter('.mov-label')->text();

                if ($cat == 'Жанр:') {
                    $cats = $node->filter('.mov-desc')->text();
                }
            });
            $category = explode(', ', $cats);
            //
            foreach ($category as &$val) {
                if (in_array(['title' => $val], $categories)) {
                    $val = array_search(['title' => $val], $categories);
                } else {
                    $categories[$category_id] = ['title' => $val];
                    $val = $category_id++;
                }
            }

            $movies[] = [
                'title' => $request->filter('.full-title > .orig-name')->text(),
                'url' => $request->filter('.vdd-element > .tabs-b > iframe')->attr('src'),
                'image_url' => $url . $request->filter('.mov-img > img')->attr('src'),
                'categories' => $category,
            ];
        }

        Category::query()->insert($categories);
        foreach ($movies as $movie) {
            $m = Movie::create([
                'title' => $movie['title'],
                'video_url' => $movie['url'],
                'image_url' => $movie['image_url']
            ]);
            $m->categories()->attach($movie['categories']);
        }
    }
}
