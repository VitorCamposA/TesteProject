<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use App\Services\HistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class WordController extends Controller
{
    protected $wordService;
    protected $historyService;

    public function __construct(WordService $wordService, HistoryService $historyService)
    {
        $this->wordService = $wordService;
        $this->historyService = $historyService;
    }

    public function getWordData(Request $request): JsonResponse
    {
        $word = $request->input('word') ?? '';
        $word = $this->wordService->getWord($word);
        $wordData = $this->wordService->getWordData($word->word);

        if (!$word && !$wordData) {
            return response()->json([
                'message' => 'Word not found'
            ], 400);
        }

        $this->historyService->addToHistory(JWTAuth::user()->id, $word->id);

        return response()->json([
            'word' => $wordData
        ]);
    }

    public function getWords(Request $request): JsonResponse
    {
        if ($request->has('search') && $request->input('search')) {
            $request->validate([
                'search' => 'required|string',
                'limit' => 'integer|min:1|max:20'
            ]);

            $query = $request->input('search');
            $perPage = $request->input('limit', 4);
            $page = $request->input('page', 1);

            $words = $this->wordService->searchWords($query, $perPage, $page);

            return response()->json([
                'results' => $words->pluck('word'),
                'totalDocs' => $words->total(),
                'previous' => $words->currentPage() > 1 ? $words->currentPage() - 1 : null,
                'next' => $words->hasMorePages() ? $words->currentPage() + 1 : null,
                'hasNext' => $words->hasMorePages(),
                'hasPrev' => $words->currentPage() > 1
            ]);
        }

        $perPage = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $words = $this->wordService->getWordsPaginated($perPage, $page);

        return response()->json([
            'results' => $words->pluck('word'),
            'totalDocs' => $words->total(),
            'previous' => $words->currentPage() > 1 ? $words->currentPage() - 1 : null,
            'next' => $words->hasMorePages() ? $words->currentPage() + 1 : null,
            'hasNext' => $words->hasMorePages(),
            'hasPrev' => $words->currentPage() > 1
        ]);
    }
}
