<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], $page = 1, $pageSize = 10): LengthAwarePaginator;
}
