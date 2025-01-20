<?php

use App\Console\Commands\ScrapeNewsArticles;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(ScrapeNewsArticles::class, ['news-api'])->hourly();
Schedule::command(ScrapeNewsArticles::class, ['nyt'])->hourly();
