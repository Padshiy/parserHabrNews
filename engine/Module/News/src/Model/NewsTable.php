<?php


namespace App\News\Model;


use Krugozor\Database\Mysql;

/**
 * Class NewsTable
 * @package App\News\Model
 */
class NewsTable
{
    /**
     * @var Mysql
     */
    private $db;

    /**
     * NewsTable constructor.
     * @throws \Krugozor\Database\MySqlException
     */
    public function __construct()
    {
        $host = 'localhost';
        $login = 'root';
        $pass = 'cnfczy41946339';
        $table = 'habr';
        $this->db = Mysql::create($host, $login, $pass)->setDatabaseName($table)->setCharset("utf8");;
    }

    /**
     * @param $url
     * @return bool
     */
    public function checkNewsWithUrl($url): bool
    {
        $sql = "SELECT * FROM `news` WHERE `link` = '?s';";

        try{
            $result = $this->db->query($sql, $url);
        } catch (\Exception $exception) {
            return false;
        }

        return $result->getNumRows() > 0;
    }

    /**
     * @param $news
     * @return bool
     */
    public function setNews($news): bool
    {
        $sql = "INSERT INTO `news`(`title`, `value`, `link`) VALUES (?as);";

        try{
            $this->db->query($sql, $news);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param $start
     * @param $countNews
     * @return array
     */
    public function getNews($start, $countNews): array
    {
        $sql = "SELECT * FROM `news` WHERE 1 ORDER BY `id` LIMIT ?i, ?i;";

        try{
            $result = $this->db->query($sql, $start, $countNews);
        } catch (\Exception $exception) {
            return [];
        }

        if ($result->getNumRows() === 0) {
            return [];
        }

        return $result->fetchAssocArray();
    }

    /**
     * @return int
     */
    public function getCountNews(): int
    {
        $sql = "SELECT `id` FROM `news` WHERE 1;";

        try{
            $result = $this->db->query($sql);
        } catch (\Exception $exception) {
            return 0;
        }

        return $result->getNumRows();
    }
}