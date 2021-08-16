<?php

namespace App\Entity;

class FilmSearch
{

    /**
     * @var string
     */
    private $keyWord;


    public function setKeyWord($keyWord)
    {
        $this->keyWord = $keyWord;
    }

    public function getKeyWord()
    {
        return $this->keyWord;
    }
}
