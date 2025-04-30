<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use App\Exceptions\WordNotFoundException;
use App\Exceptions\WordAlreadyFavoritedException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function addFavorite(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $word = $request->input('word');

            $this->favoriteService->addFavorite($userId, $word);

            return response()->json([
                'message' => 'Word added to favorites successfully',
            ]);
        } catch (WordNotFoundException|WordAlreadyFavoritedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function removeFavorite(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $word = $request->input('word');

            $result = $this->favoriteService->removeFavorite($userId, $word);

            if (!$result) {
                return response()->json([
                    'message' => 'Favorite not found'
                ], 400);
            }

            return response()->json([
                'message' => 'Word removed from favorites successfully'
            ]);
        } catch (WordNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function getUserFavorites(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $perPage = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $favorites = $this->favoriteService->getUserFavorites($userId, $perPage, $page);

        return response()->json([
            'results' => $favorites->map(function ($favorite) {
                return [
                    'word' => $favorite->word->word,
                    'added' => $favorite->created_at->toIso8601String()
                ];
            }),
            'totalDocs' => $favorites->total(),
            'page' => $favorites->currentPage(),
            'totalPages' => $favorites->lastPage(),
            'hasNext' => $favorites->hasMorePages(),
            'hasPrev' => $favorites->currentPage() > 1
        ]);
    }

    public function checkFavorite(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $word = $request->input('word');

            $isFavorited = $this->favoriteService->isFavorited($userId, $word);

            return response()->json([
                'is_favorited' => $isFavorited
            ]);
        } catch (WordNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
