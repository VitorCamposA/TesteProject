<?php

namespace App\Services;

use App\Repositories\WordRepository;

class WordService
{
    protected $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function getWord($word)
    {
        return $this->wordRepository->getWord($word);
    }

    public function createMultipleWords(array $words)
    {
        $createdWords = [];
        foreach ($words as $word) {
            $createdWords[] = $this->wordRepository->createWord($word);
        }
        return $createdWords;
    }

    public function createWord($wordData)
    {
        return $this->wordRepository->createWord($wordData);
    }

    public function updateWord($word, $wordData)
    {
        return $this->wordRepository->updateWord($word, $wordData);
    }

    public function deleteWord($word)
    {
        return $this->wordRepository->deleteWord($word);
    }

    public function getWords()
    {
        return $this->wordRepository->getWords();
    }

    public function searchWords(string $query, int $perPage = 4, int $page = 1)
    {
        return $this->wordRepository->searchWords($query, $perPage, $page);
    }

    public function getWordsPaginated(int $perPage = 20, int $page = 1)
    {
        return $this->wordRepository->getWordsPaginated($perPage, $page);
    }
}

