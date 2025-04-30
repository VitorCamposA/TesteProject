<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function signup(Request $request): JsonResponse
    {
        try {
            $userData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = $this->userService->createUser($userData);
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'id' => substr(md5($user->id), 0, 24),
                'name' => $user->name,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível criar o usuário. Por favor, tente novamente.'
            ], 400);
        }
    }

    public function signin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciais inválidas'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Não foi possível criar o token'
            ], 400);
        }

        $user = JWTAuth::user();

        return response()->json([
            'id' => substr(md5($user->id), 0, 24),
            'name' => $user->name,
            'token' => $token
        ]);
    }

    public function getHistory(Request $request): JsonResponse
    {
        $user = JWTAuth::user();
        $page = $request->input('page', 1);
        $perPage = $request->input('limit', 4);

        $histories = $this->userService->getUserWithHistories($user->id, $perPage, $page);

        $formattedHistories = $histories->map(function ($history) {
            return [
                'word' => $history->word->word,
                'added' => $history->updated_at->toIso8601String()
            ];
        });

        return response()->json([
            'results' => $formattedHistories,
            'totalDocs' => $histories->total(),
            'page' => $histories->currentPage(),
            'totalPages' => $histories->lastPage(),
            'hasNext' => $histories->hasMorePages(),
            'hasPrev' => $histories->currentPage() > 1
        ]);
    }

    public function getUserData(Request $request): JsonResponse
    {
        $user = JWTAuth::user();
        $userWithFavorites = $this->userService->getUserWithFavorites($user->id);

        $favorites = $userWithFavorites->favoritedWords;
        $favoritesMessage = $favorites->isEmpty()
            ? "Nenhuma palavra favoritada"
            : "Usuário favoritou " . $favorites->count() . " palavra(s)";

        return response()->json([
            'user' => [
                'id' => substr(md5($userWithFavorites->id), 0, 24),
                'name' => $userWithFavorites->name,
                'email' => $userWithFavorites->email,
                'favorites' => $favoritesMessage
            ]
        ]);
    }
}
