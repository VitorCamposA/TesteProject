<?php

namespace App\Repositories;

use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class HistoryRepository
{
    /**
     * Create a new history record
     *
     * @param int $userId
     * @param int $wordId
     * @return History
     */
    public function createHistory(int $userId, int $wordId): History
    {
        return History::create([
            'user_id' => $userId,
            'word_id' => $wordId
        ]);
    }

    /**
     * Get all histories for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getHistories(int $userId): Collection
    {
        return History::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Delete a history record
     *
     * @param int $userId
     * @param int $wordId
     * @return bool
     */
    public function deleteHistory(int $userId, int $wordId): bool
    {
        $history = History::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->first();

        if (!$history) {
            return false;
        }

        return $history->delete();
    }

    /**
     * Update a history record's timestamp
     *
     * @param int $userId
     * @param int $wordId
     * @return bool
     */
    public function updateHistory(int $userId, int $wordId): bool
    {
        $history = History::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->first();

        if (!$history) {
            return false;
        }

        $history->updated_at = now();
        return $history->save();
    }

    /**
     * Find a specific history record
     *
     * @param int $userId
     * @param int $wordId
     * @return History|null
     */
    public function findHistory(int $userId, int $wordId): ?History
    {
        return History::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->first();
    }
}
