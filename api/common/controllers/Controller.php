<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/3/2
 * Time: 下午2:07
 */

namespace api\common\controllers;
use yii\web\Response;

class Controller extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }

    /**
     * 返回数据到客户端
     * @author zhaiyu
     * @startDate 20160310
     * @upDate 20160310
     * @param array $content 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param String $handler 默认的JSONP格式返回的处理方法是callback
     * @return void
     */
    protected function ajaxReturn($content = array(), $type = 'JSON', $handler = 'callback') {
        $format = array('code', 'msg', 'data');
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
}