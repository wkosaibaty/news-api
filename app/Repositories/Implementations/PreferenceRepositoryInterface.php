<?php

namespace App\Repositories\Implementations;

use App\Models\User;

interface PreferenceRepositoryInterface
{
    public function getUserPreferences(User $user): array;
    public function storeUserPreferences(User $user, array $data): void;
}
