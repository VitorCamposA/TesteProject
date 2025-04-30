<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;
use App\Repositories\WordRepository;
use App\Models\Favorite;
use App\Exceptions\WordNotFoundException;
use App\Exceptions\WordAlreadyFavoritedException;
use Illuminate\Database\Eloquent\Collection;

class FavoriteService
{
    protected $favoriteRepository;
    protected $wordRepository;

    public function __construct(FavoriteRepository $favoriteRepository, WordRepository $wordRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->wordRepository = $wordRepository;
    }

    /**
     * @throws WordNotFoundException
     * @throws WordAlreadyFavoritedException
     */
    public function addFavorite(int $userId, string $word): Favorite
    {
        $wordModel = $this->wordRepository->getWord($word);
        if (!$wordModel) {
            throw new WordNotFoundException($word);
        }

        if ($this->favoriteRepository->isFavorited($userId, $wordModel->id)) {
            throw new WordAlreadyFavoritedException();
        }

        return $this->favoriteRepository->addFavorite($userId, $wordModel->id);
    }

    /**
     * @throws WordNotFoundException
     */
    public function removeFavorite(int $userId, string $word): bool
    {
        $wordModel = $this->wordRepository->getWord($word);
        if (!$wordModel) {
            throw new WordNotFoundException($word);
        }

        return $this->favoriteRepository->removeFavorite($userId, $wordModel->id);
    }

    public function getUserFavorites(int $userId, int $perPage = 20, int $page = 1)
    {
        return $this->favoriteRepository->getUserFavorites($userId, $perPage, $page);
    }

    /**
     * @throws WordNotFoundException
     */
    public function isFavorited(int $userId, string $word): bool
    {
        $wordModel = $this->wordRepository->getWord($word);
        if (!$wordModel) {
            throw new WordNotFoundException($word);
        }

        return $this->favoriteRepository->isFavorited($userId, $wordModel->id);
    }
}
