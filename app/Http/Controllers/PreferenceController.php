<?php

namespace App\Http\Controllers;

use App\Http\Requests\Preference\StorePreferenceRequest;
use App\Repositories\Implementations\PreferenceRepositoryInterface;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    private PreferenceRepositoryInterface $preferenceRepository;

    public function __construct(PreferenceRepositoryInterface $preferenceRepository) {
        $this->preferenceRepository = $preferenceRepository;
    }

    public function index(Request $request)
    {
        $response = $this->preferenceRepository->getUserPreferences($request->user());
        return $this->sendResponse($response);
    }

    public function store(StorePreferenceRequest $request)
    {
        $data = $request->validated();
        $this->preferenceRepository->storeUserPreferences($request->user(), $data);
        return $this->sendResponse(null);
    }
}
