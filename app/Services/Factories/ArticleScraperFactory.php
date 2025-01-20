<?php

namespace App\Services\Factories;

use App;
use App\Services\Implementations\NewsApiScraper;
use App\Services\Implementations\NewYorkTimesScraper;
use App\Services\Interfaces\ArticleScraperInterface;
use Exception;

class ArticleScraperFactory
{
    public static function make(string $source): ArticleScraperInterface {
        switch ($source) {
            case "news-api":
                return App::make(NewsApiScraper::class);
            case "nyt":
                return App::make(NewYorkTimesScraper::class);
            default:
                throw new Exception("Invalid scraper source.");
        }
    }
}
