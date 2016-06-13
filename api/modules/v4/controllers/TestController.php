<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use api\common\models\LoginForm;
use yii\web\Response;
use api\common\models\member;
use Yii;
use crazyfd\qiniu\Qiniu;
use common\components\Getui; // 引用个推工具类
class TestController extends Controller
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

    protected function verbs(){
        return [
            'sign'=>['POST'],
            'send'=>['GET'],
            'login'=>['POST'],
            'forget'=>['POST'],
        ];
    }
    public function actionSend()
    {
        $getui =  new Getui();
        //$getui->pushMessageToApp();// 群推
        $res =$getui->pushSingle($title='你牛逼',$content='{"title": "这样真滴好么", "content": "哈哈哈哈你死定了" }',$uids=[297]);// 单推 4fcd96017e60fde64edc72bf46648dd1
        print_r($res);
       exit;
     
    }
    
    public function actionUpload()
    {
        $ak = 'OL3qoivVQhxkRWAL_W3CRs435m1Y5CeJVfkKIDg-';
        $sk = 'mPEylNDXx64U84HjkEcUwJyXg1B40-GUUfC_TR8T';
        $domain = 'http://api.test.ohmate.com.cn';
        $qiniu = new Qiniu($ak, $sk,$domain, 'mdup');
        $key = time();
        $qiniu->uploadFile($_FILES['tmp_name'],$key);
        $url = $qiniu->getLink($key);

    }
    public function actionDelete()
    {
        echo'success';
        Yii::$app->cache->flush();exit;
    }
           
}