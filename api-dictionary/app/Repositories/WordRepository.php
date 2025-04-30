<?php

namespace App\Repositories;

use App\Models\Word;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WordRepository
{
    public function getWord($word): ?Word
    {
        return Word::where('word', $word)->first();
    }

    public function createWord(array $wordData): Word
    {
        return Word::create($wordData);
    }

    public function updateWord($word, array $wordData): bool
    {
        $word = $this->getWord($word);
        return $word->update($wordData);
    }

    public function deleteWord($word): bool
    {
        $word = $this->getWord($word);
        return $word->delete();
    }

    public function getWords()
    {
        return Word::all();
    }

    public function searchWords(string $query, int $perPage = 20, int $page = 1)
    {
        return Word::where('word', 'like', "%{$query}%")
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getWordsPaginated(int $perPage = 20, int $page = 1)
    {
        return Word::paginate($perPage, ['*'], 'page', $page);
    }
}
