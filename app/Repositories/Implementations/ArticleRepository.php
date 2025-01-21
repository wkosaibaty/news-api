<?php

namespace App\Repositories\Implementations;

use App\Models\Article;
use App\Models\Category;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], $page = 1, $pageSize = 10): LengthAwarePaginator {
        $query = $this->buildQuery($filters)
            ->with('author')
            ->orderBy('published_at', 'desc');
        return $query->paginate($pageSize, ['*'], 'page', $page);
    }

    public function find(int $id): Model {
        return Article::where('id', $id)
            ->with('category')
            ->with('author')
            ->with('source')
            ->firstOrFail();
    }

    private function buildQuery(array $filters = []): Builder {
        $query = Article::query();

        if (!empty($filters['search'])) {
            $query->where(function($query) use($filters) {
                $query
                    ->where('title', 'like', "%".$filters['search']."%")
                    ->orWhere('content', 'like', "%".$filters['search']."%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['source_id'])) {
            $query->where('source_id', $filters['source_id']);
        }

        if (!empty($filters['category_ids'])) {
            $query->whereIn('category_id', $filters['category_ids']);
        }

        if (!empty($filters['source_ids'])) {
            $query->whereIn('source_id', $filters['source_ids']);
        }

        if (!empty($filters['author_ids'])) {
            $query->whereIn('author_id', $filters['author_ids']);
        }

        if (!empty($filters['published_at_from'])) {
            $query->where('published_at', '>=', $filters['published_at_from']);
        }

        if (!empty($filters['published_at_to'])) {
            $query->where('published_at', '<=', $filters['published_at_to']);
        }

        return $query;
    }
}
