<?php

namespace App\Services;

use App\Repositories\WordRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WordService
{
    protected $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function getWordData($word)
    {
        $cacheKey = 'dictionary:' . strtolower($word);
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return $cachedData;
        }

        $result = null;

        $client = new Client();
        try {
            $response = $client->get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
            $data = json_decode($response->getBody(), true);

            if (!empty($data[0])) {
                $wordData = $data[0];

                $definitions = [];
                foreach ($wordData['meanings'] as $meaning) {
                    foreach ($meaning['definitions'] as $definition) {
                        $definitions[] = [
                            'definition' => $definition['definition'],
                            'partOfSpeech' => $meaning['partOfSpeech'],
                            'example' => $definition['example'] ?? null
                        ];
                    }
                }

                $result = [
                    'word' => $wordData['word'],
                    'definitions' => $definitions
                ];

                // Store in cache for 24 hours
                Cache::put($cacheKey, $result, 86400);
            }
        } catch (\Exception $e) {
            return null;
        }
        return $result;
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

