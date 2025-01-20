<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorController extends Controller
{
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    public function index()
    {
        $authors = $this->authorRepository->all();
        return $this->sendResponse(AuthorResource::collection($authors));
    }
}
