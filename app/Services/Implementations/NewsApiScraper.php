<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\Interfaces\ArticleScraperInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiScraper implements ArticleScraperInterface
{
    private $categories = ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'];

    private ArticleRepositoryInterface $articleRepository;
    private AuthorRepositoryInterface $authorRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private SourceRepositoryInterface $sourceRepository;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        AuthorRepositoryInterface $authorRepository,
        CategoryRepositoryInterface $categoryRepository,
        SourceRepositoryInterface $sourceRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->sourceRepository = $sourceRepository;
    }

    public function scrape(): void {
        foreach ($this->categories as $category) {
            $this->scrapeByCategory($category);
        }
    }

    private function scrapeByCategory(string $categoryName) {
        $data = $this->fetchData($categoryName);
        if (empty($data)) {
            return;
        }

        $category = $this->categoryRepository->findOrCreate(['name' => $categoryName]);

        foreach ($data as $article) {
            if (empty($article['author']) || empty($article['source']) || empty($article['source']['name'])) {
                continue;
            }

            $author = $this->authorRepository->findOrCreate(['name' => $article['author']]);
            $source = $this->sourceRepository->findOrCreate(['name'=> $article['source']['name']]);

            $this->articleRepository->findOrCreate([
                'title' => $article['title'],
                'author_id' => $author->id,
            ],[
                'content' => $article['description'],
                'image_url' => $article['urlToImage'],
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['publishedAt']),
            ]);
        }
    }

    private function fetchData(string $categoryName) {
        $url = config(key: 'scrapers.news_api.url');
        $apiKey = config(key: 'scrapers.news_api.api_key');
        $response = Http::get($url, [
            'country' => 'us',
            'category' => $categoryName,
            'apiKey' => $apiKey,
        ]);
        if (!$response->successful()) {
            return null;
        }

        return $response->json()['articles'];
    }
}
