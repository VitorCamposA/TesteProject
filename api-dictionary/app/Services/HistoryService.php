<?php

namespace App\Services;

use App\Repositories\HistoryRepository;
use App\Models\History;
use Illuminate\Database\Eloquent\Collection;

class HistoryService
{
    protected $historyRepository;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    /**
     * Add a word to user's history
     *
     * @param int $userId
     * @param int $wordId
     * @return History
     */
    public function addToHistory(int $userId, int $wordId): History
    {
        $existingHistory = $this->historyRepository->findHistory($userId, $wordId);

        if ($existingHistory) {
            $this->historyRepository->updateHistory($userId, $wordId);
            return $existingHistory;
        }

        return $this->historyRepository->createHistory($userId, $wordId);
    }

    /**
     * Get user's history
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserHistory(int $userId): Collection
    {
        return $this->historyRepository->getHistories($userId);
    }

    /**
     * Remove a word from user's history
     *
     * @param int $userId
     * @param int $wordId
     * @return bool
     */
    public function removeFromHistory(int $userId, int $wordId): bool
    {
        return $this->historyRepository->deleteHistory($userId, $wordId);
    }

    /**
     * Clear all user's history
     *
     * @param int $userId
     * @return bool
     */
    public function clearUserHistory(int $userId): bool
    {
        $histories = $this->historyRepository->getHistories($userId);
        
        foreach ($histories as $history) {
            $this->historyRepository->deleteHistory($userId, $history->word_id);
        }

        return true;
    }
}
