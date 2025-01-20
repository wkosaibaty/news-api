<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function all(): Collection {
        return $this->model::all();
    }
}
