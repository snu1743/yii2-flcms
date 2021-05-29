<?php


namespace fl\cms\entities;

use yii;
use fl\cms\entities\base\Config;
use yii\base\Action;

class Main extends Action
{
    public function run()
    {
        $session = Yii::$app->session;
        $request = $this->getRequest();
        return $request;
//        return $session->get('fl_cms');
    }

    private function getRequest()
    {
        return yii::$app->response->post();
    }

    /**
     * Perform
     * @param array $request
     * @return array
     */
    public function perform(array $request, $asArray = true)
    {
//        try{
            $configPath = __DIR__ . "/{$request['entity']}/config.php";
            if(file_exists($configPath)) {
                //TODO-require-config $config = require_once $configPath;
                $config = require $configPath;
            } else {
                $config = Config::get($request);
            }
            if(isset($request['only_config']) && $request['only_config']) {
                return Config::set($config, $request);
            }
            if(isset($request['class_name'])) {
                $n = explode("_", $request['class_name']);
            } else {
                $n = explode("_", $request['action_name']);
            }
            $className = '';
            foreach ($n as $segment) {
                $className = $className . ucfirst($segment);
            }
            $class = '\\' . __NAMESPACE__ . "\\{$request['entity']}\\"  . $className;
            if($request['entity'] === 'dynamic_instances' && !class_exists($class)) {
                $class = '\\' . __NAMESPACE__ . "\\{$request['entity']}\\" . 'ExecMethod';
            }
            $model = new $class($request['action_name'], $config, $request);
            if($asArray) {
                return $model->_response->getAsArray();
            } else {
                return $model->_response;
            }
//        } catch (\yii\web\UnauthorizedHttpException $e) {
//            throw new $e();
//        } catch (\Throwable $e) {
////            $response = new ApiResponse();
////            $response->setStatus($response::FAILURE);
////            Yii::$app->response->statusCode = 401;
////            http_response_code ( 401 );
//            print_r($e->getMessage()); exit;
//        }
    }

}
