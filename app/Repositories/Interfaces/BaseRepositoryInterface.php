<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): Model;
    public function findOrCreate(array $attributes = [], array $values = []): Model;
}
