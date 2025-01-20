<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceResource;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    private SourceRepositoryInterface $sourceRepository;

    public function __construct(SourceRepositoryInterface $sourceRepository) {
        $this->sourceRepository = $sourceRepository;
    }

    public function index()
    {
        $sources = $this->sourceRepository->all();
        return $this->sendResponse(SourceResource::collection($sources));
    }
}
