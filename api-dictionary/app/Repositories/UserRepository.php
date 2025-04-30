<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    public function find(int $userId): ?User
    {
        return User::where('id', $userId)->first();
    }

    public function create(array $userData): User
    {
        return User::create($userData);
    }

    public function update(int $userId, array $userData): bool
    {
        return User::where('id', $userId)->update($userData);
    }

    public function delete(int $userId): bool
    {
        return User::destroy($userId);
    }

    public function getAll(): Collection
    {
        return User::all();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUserWithFavorites(int $userId): null|User|Model
    {
        return User::with('favoritedWords')->find($userId);
    }

    public function getUserWithHistories(int $userId, int $perPage = 4, int $page = 1)
    {
        return User::with(['histories.word'])
            ->where('id', $userId)
            ->first()
            ->histories()
            ->with('word')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
