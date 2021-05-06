<?php


namespace App\News\Controller;


use App\News\Lib\NewsLib;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class NewsController
 * @package App\News\Controller
 */
class NewsController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var NewsLib
     */
    private $lib;

    /**
     * NewsController constructor.
     */
    public function __construct()
    {
        $path = realpath(dirname(__DIR__) . '/Views/');
        $loader = new FilesystemLoader($path);
        $this->twig = new Environment($loader, ['debug' => true]);
        $this->twig->addExtension(new DebugExtension());
        $this->lib = new NewsLib();
    }

    /**
     * @param int $page
     * @return string|null
     */
    public function getNewsList($page = 1): ?string
    {
        $news = $this->lib->getNews($page);

        try{
            return $this->twig->render('news.html', ['newsArray' => $news]);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            return '';
        }
    }

    /**
     * @return string
     */
    public function getPagination(): string
    {
        $pagination = $this->lib->getCountNews();

        try{
            return $this->twig->render('pagination.html', ['pagination' => $pagination]);
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * @return string|null
     */
    public function getMain(): ?string
    {
        $news = $this->lib->getNews(1);
        $pagination = $this->lib->getCountNews();
        try{
            return $this->twig->render('bodyNews.html', ['newsArray' => $news, 'pagination' => $pagination]);
        } catch (\Exception $exception) {
            return '';
        }
    }
}