<?php


namespace App\News\Controller;


use App\News\Lib\ParserLib;

/**
 * Class ParserController
 * @package App\News\Controller
 */
class ParserController
{
    /**
     * @return bool
     */
    public function parsing()
    {
        $parsingUrl = 'https://habr.com/ru/';
        $lib = new ParserLib();
        //скачиваем главную станицу
        $file = $lib->download($parsingUrl);
        //получаем ссылки
        $urls = $lib->getLinksNews($file);
        unset($file);
        //парсим страницы
        return $lib->parsing($urls);
    }
}