<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUser(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    public function createUser(array $userData): User
    {
        return $this->userRepository->create($userData);
    }

    public function updateUser(int $userId, array $userData): bool
    {
        return $this->userRepository->update($userId, $userData);
    }

    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getUserWithFavorites(int $userId): ?User
    {
        return $this->userRepository->getUserWithFavorites($userId);
    }

    public function getUserWithHistories(int $userId, int $perPage = 4, int $page = 1)
    {
        return $this->userRepository->getUserWithHistories($userId, $perPage, $page);
    }
}
