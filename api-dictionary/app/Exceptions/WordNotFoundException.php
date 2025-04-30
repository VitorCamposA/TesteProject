<?php

namespace App\Exceptions;

class WordNotFoundException extends DictionaryException
{
    public function __construct(string $word)
    {
        parent::__construct("Word '{$word}' not found");
    }
}
