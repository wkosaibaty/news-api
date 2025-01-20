<?php

namespace App\Console\Commands;

use App\Services\Factories\ArticleScraperFactory;
use Illuminate\Console\Command;

class ScrapeNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-news-articles {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news articles from the source passed as a parameter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ArticleScraperFactory::make($this->argument('source'))->scrape();
    }
}
