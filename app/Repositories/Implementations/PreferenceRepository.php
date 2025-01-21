<?php

namespace App\Repositories\Implementations;

use App\Models\User;

class PreferenceRepository implements PreferenceRepositoryInterface
{
    public function getUserPreferences(User $user): array
    {
        return [
            'category_ids' => $user->preferredCategories()->pluck('id')->toArray(),
            'author_ids' => $user->preferredAuthors()->pluck('id')->toArray(),
            'source_ids' => $user->preferredSources()->pluck('id')->toArray(),
        ];
    }

    public function storeUserPreferences(User $user, array $data): void
    {
        $user->preferredCategories()->sync($data['category_ids']);
        $user->preferredAuthors()->sync($data['author_ids']);
        $user->preferredSources()->sync($data['source_ids']);
    }
}
