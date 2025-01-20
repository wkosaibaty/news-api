<?php

namespace App\Repositories\Implementations;

use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;

class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    public function __construct(Source $model)
    {
        parent::__construct($model);
    }
}
