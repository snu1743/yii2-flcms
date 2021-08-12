<?php


namespace fl\cms\helpers\cms;

use yii;
use fl\cms\helpers\page\View;
use fl\cms\controllers\PageController;

class Main
{
    /**
     * Инициализация работы CMS, в случае если в основной логике проекта страницы с текущем адресом не существует(статус 404), пробуем
     * найти её в CMS. Если страница существует и достаточно прав, то отображаем её, если не существует то возвращаем статус 404.
     */
    public static function init()
    {
        $exception = Yii::$app->errorHandler->exception;
        if($exception && $exception->statusCode == 404){
            header("HTTP/1.0 200");
//            try {
                $page = new PageController('app', 'fl-cms');
                $request = yii::$app->request->get();
                if(isset($request['cms-page-edit-mod']) && $request['cms-page-edit-mod'] > 0 && $request['cms-page-edit-mod'] <= 1){
                    echo $page->actionEdit();
                } else {
                    echo $page->actionView();
                }
                exit;
//            } catch (\Throwable $e) {
//                echo $e->getMessage();
//            }
        }
    }
}