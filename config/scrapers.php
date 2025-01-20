<?php

return [
    "news_api" => [
        "url" => env("NEWS_API_URL", ''),
        "api_key" => env("NEWS_API_KEY", ''),
    ],
    "nyt" => [
        "url" => env("NY_TIMES_URL", ''),
        "api_key" => env("NY_TIMES_KEY", ''),
    ]
];
