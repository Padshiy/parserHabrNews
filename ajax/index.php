<?php
require_once dirname(__DIR__ ). '/engine/autoload.php';

if(isset($_POST['action']) && $_POST['action'] === 'download'){
    $result = (new \App\News\Controller\ParserController())->parsing();
    echo json_encode(['success' => true, 'msg' => $result]);
    die();
}

if(isset($_POST['page'], $_POST['action']) && $_POST['action'] === 'update'){
    $page = (int)$_POST['page'];
    $result = (new \App\News\Controller\NewsController())->getNewsList($page);
    echo json_encode(['success' => true, 'msg' => $result]);
    die();
}

echo json_encode(['success' => false, 'msg' => 'data error']);
die();