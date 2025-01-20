<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\GetArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\PageResource;
use App\Repositories\Interfaces\ArticleRepositoryInterface;

class ArticleController extends Controller
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function index(GetArticlesRequest $request)
    {
        $filters = $request->validated();
        $page = $request->get('page', 1);
        $pageSize = $request->get('page_size', 10);

        $articles = $this->articleRepository->paginate($filters, $page, $pageSize);
        $response = new PageResource($articles, ArticleResource::class);
        return $this->sendResponse($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = $this->articleRepository->find($id);
        return $this->sendResponse(ArticleResource::make($article));
    }
}
