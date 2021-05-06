<?php


namespace App\News\Lib;


use App\News\Model\NewsTable;

/**
 * Class ParserLib
 * @package App\News\Lib
 */
class ParserLib
{
    /**
     * @var int
     *
     * лимит новостей
     */
    protected $countParserNews = 5;
    /**
     * @var NewsTable
     */
    protected $table;

    /**
     * ParserLib constructor.
     */
    public function __construct()
    {
        $this->table = new NewsTable();
    }

    /**
     * @param $urls
     * @return bool
     */
    public function parsing($urls): bool
    {
        foreach ($urls as $url) {
            if($this->table->checkNewsWithUrl($url) === true) {
                continue;
            }

            $file = $this->download($url);
            $news = $this->getParsingHtml($file);
            $news['link'] = $url;
            unset($file);
            $this->table->setNews($news);
        }
        return true;
    }

    /**
     * @param $url
     * @param int $i
     * @return bool|string
     */
    public function download($url, $i = 1)
    {
        $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
        try {
            $ch = @curl_init();
            @curl_setopt($ch, CURLOPT_URL, $url);
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 3);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 11);
            @curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            $res = @curl_exec($ch);
            $httpCode = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = @curl_error();
            $errno = @curl_errno();
            $info = @curl_getinfo($ch);
            @curl_close($ch);
            if ($httpCode === 301) {
                return download($info['url'], ++$i);
            }
            if ($httpCode !== 200 && $httpCode !== 304) {
                echo json_encode(['success' => false, 'msg' => 'Ошибка при скачивании страницы']);
                die();
            }
        } catch (\Exception $exception) {
            echo json_encode(['success' => false, 'msg' => 'Ошибка при скачивании страницы']);
            die();
        }

        return $res;
    }

    /**
     * @param $file
     * @return array
     */
    public function getLinksNews($file): array
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($file);

        $xpath = new \DOMXpath($doc);
        //получаем все ссылки на новости
        $elements = $xpath->query("//a[contains(@class,'post__title_link')]");
        $urls = [];
        for($i = 0; $i < $this->countParserNews; $i++) {
            $urls[] = $elements[$i]->getAttribute('href');
        }

        return $urls;
    }

    /**
     * @param $file
     * @return mixed
     */
    private function getParsingHtml($file)
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($file);

        $xpath = new \DOMXpath($doc);
        //получение заголовка новости
        $array['title'] = $xpath->query("//span[contains(@class,'post__title-text')]")->item(0)->nodeValue;
        //получение содержимого новости
        $array['value'] = $xpath->query("//*[@id='post-content-body']")->item(0)->nodeValue;
        //краткая новость
        $array['short'] = mb_substr($array['value'], 0, 200);
        return $array;
    }
}