<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\PageResource;
use App\Repositories\Implementations\PreferenceRepositoryInterface;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    private ArticleRepositoryInterface $articleRepository;
    private PreferenceRepositoryInterface $preferenceRepository;
    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        PreferenceRepositoryInterface $preferenceRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->preferenceRepository = $preferenceRepository;
    }

    public function index(Request $request)
    {
        $filters = [];
        $page = 1;
        $pageSize = 20;

        $user = $request->user();
        if ($user) {
            $filters = $this->preferenceRepository->getUserPreferences($user);
        }

        $articles = $this->articleRepository->paginate($filters, $page, $pageSize);
        if ($articles->total() == 0 && !empty($filters)) {
            $articles = $this->articleRepository->paginate([], $page, $pageSize);
        }

        $response = new PageResource($articles, ArticleResource::class);
        return $this->sendResponse($response);
    }
}
