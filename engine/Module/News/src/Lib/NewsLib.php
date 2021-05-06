<?php


namespace App\News\Lib;

/**
 * Class NewsLib
 * @package App\News\Lib
 */
class NewsLib extends ParserLib
{
    /**
     * @param $page
     * @return array
     */
    public function getNews($page): array
    {
        $page = $page === 1 ? 0 : (($page - 1) * $this->countParserNews);
        return $this->table->getNews($page, $this->countParserNews);
    }

    /**
     * @return array
     */
    public function getCountNews(): array
    {
        $count = $this->table->getCountNews();

        if($count === 0) {
            return [];
        }

        return @range(1, ceil($count / $this->countParserNews));
    }
}