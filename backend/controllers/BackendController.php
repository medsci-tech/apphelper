<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Backend controller
 */
class BackendController extends Controller
{
    public $layout = 'main-common';
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        //未登录
        if (\Yii::$app->user->isGuest) {
            //Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'status' => -1,
                'message' => '请先登录',
                'url' => Yii::$app->getHomeUrl()
            );
            return $this->goHome();
        }

        return true; // or false to not run the action
    }

    /**
     * Ajax方式返回数据到客户端
     * @author zhaiyu
     * @startDate 20160310
     * @upDate 20160310
     * @param array $content 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param String $handler 默认的JSONP格式返回的处理方法是callback
     * @return void
     */
    protected function ajaxReturn($content = [], $type = 'JSON', $handler = 'callback') {
        $format = ['code', 'msg', 'data'];
        $data = array_combine($format, array_pad($content, 3, ''));
        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $data = json_encode($data);
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $data = $handler . '(' . json_encode($data) . ');';
                break;
            case 'EVAL':
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                break;
        }
        exit($data);
    }

    /**
     * 获取目录的树形结构
     * @author zhaiyu
     * @startDate 20160530
     * @upDate 20160530
     * @param $directoryStructureData
     * @return array
     */
    public function TreeList($directoryStructureData){
        $tree = new TreeController($directoryStructureData, ' |- ');
        $directoryStructureTree = $tree->get_tree('id', 'name');
        $directoryStructureList = [];
        if($directoryStructureTree){
            foreach ($directoryStructureTree as $key => $val){
                $directoryStructureList[$val['id']] = $val['name'];
            }
        }
        return $directoryStructureList;
    }

}

