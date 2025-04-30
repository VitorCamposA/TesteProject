<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Model;

class FavoriteRepository
{
    public function addFavorite(int $userId, int $wordId): Favorite
    {
        return Favorite::create([
            'user_id' => $userId,
            'word_id' => $wordId
        ]);
    }

    public function removeFavorite(int $userId, int $wordId): bool
    {
        $favorite = Favorite::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->first();

        if (!$favorite) {
            return false;
        }

        return $favorite->delete();
    }

    public function isFavorited(int $userId, int $wordId): bool
    {
        return Favorite::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->exists();
    }

    public function getUserFavorites(int $userId, int $perPage = 20, int $page = 1)
    {
        return Favorite::where('user_id', $userId)
            ->with('word')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
