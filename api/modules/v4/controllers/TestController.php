<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use api\common\models\LoginForm;
use yii\web\Response;
use api\common\models\member;
use Yii;
use crazyfd\qiniu\Qiniu;
use common\components\Getui; // 引用个推工具类
require_once('../../common/components/getui/' . 'IGt.Push.php');
require_once('../../common/components/getui/igetui/IGt.AppMessage.php');
require_once('../../common/components/getui/igetui/IGt.APNPayload.php');
require_once('../../common/components/getui/igetui/template/IGt.BaseTemplate.php');
require_once('../../common/components/getui/IGt.Batch.php');
require_once('../../common/components/getui/igetui/utils/AppConditions.php');
//http的域名
define('HOST','http://sdk.open.api.igexin.com/apiex.htm');


//定义常量, appId、appKey、masterSecret 采用本文档 "第二步 获取访问凭证 "中获得的应用配置               
define('APPKEY','8Trw9aVxom5P3MpbITR7h');
define('APPID','EuCgiztOJC8FGoq2GVatO9');
define('MASTERSECRET','s0ER22qWDU6uvCWIik9t3');
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
       $getui->pushSingle($title='你牛逼',$content='牛逼在哪里啊',$uids=[288,202]);// 单推 ios
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
}