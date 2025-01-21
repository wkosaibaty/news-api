<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\Interfaces\ArticleScraperInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TheGuardianScraper implements ArticleScraperInterface
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
    )
    {
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

        foreach ($data as $article) {
            if (empty($article['fields'])) {
                continue;
            }

            $author = $this->authorRepository->findOrCreate(['name'=> $article['fields']['byline']]);
            $category = $this->categoryRepository->findOrCreate(['name'=> $article['pillarName']]);
            $source = $this->sourceRepository->findOrCreate(['name'=> $article['fields']['publication']]);

            $a = $this->articleRepository->findOrCreate([
                'title' => $article['webTitle'],
                'author_id' => $author->id,
            ],[
                'content' => $article['fields']['bodyText'],
                'image_url' => $article['fields']['thumbnail'],
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['fields']['firstPublicationDate']),
            ]);
        }
    }

    private function fetchData() {
        $url = config(key: 'scrapers.guardian.url');
        $apiKey = config(key: 'scrapers.guardian.api_key');
        $response = Http::get($url, [
            'api-key' => $apiKey,
            'show-fields' => 'byline,firstPublicationDate,thumbnail,bodyText,publication'
        ]);
        if (!$response->successful()) {
            return null;
        }

        return $response->json()['response']['results'];
    }
}
