<?php

namespace App\Exceptions;

class WordAlreadyFavoritedException extends DictionaryException
{
    public function __construct()
    {
        parent::__construct('Word added to favorites successfully', 200);
    }


}
