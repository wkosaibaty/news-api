<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\Interfaces\ArticleScraperInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimesScraper implements ArticleScraperInterface
{
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
        $data = $this->fetchData();
        if (empty($data)) {
            return;
        }

        $source = $this->sourceRepository->findOrCreate(['name'=> 'The New York Times']);

        foreach ($data as $article) {
            if (empty($article['byline']) || empty($article['subsection'])) {
                continue;
            }

            $author = $this->getAuthor($article);
            $category = $this->categoryRepository->findOrCreate(['name'=> $article['subsection']]);
            $image_url = $this->getImageUrl($article);

            $this->articleRepository->findOrCreate([
                'title' => $article['title'],
                'author_id' => $author->id,
            ],[
                'content' => $article['abstract'],
                'image_url' => $image_url,
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['published_date']),
            ]);
        }
    }

    private function fetchData() {
        $url = config(key: 'scrapers.nyt.url');
        $apiKey = config(key: 'scrapers.nyt.api_key');
        $response = Http::get($url, ['api-key' => $apiKey]);
        if (!$response->successful()) {
            return null;
        }

        return $response->json()['results'];
    }

    private function getImageUrl($article) {
        if(!empty($article['multimedia'])) {
            return null;
        }
        return $article['multimedia'][0]['url'];
    }

    private function getAuthor($article) {
        $authorName = $this->removePrefixFromString($article['byline'], "By");
        return $this->authorRepository->findOrCreate(['name' => $authorName]);
    }

    private function removePrefixFromString($string, $prefix) {
        if (strpos($string, $prefix) === 0) {
            return substr($string, strlen($prefix));
        }
            return $string;
    }
}
