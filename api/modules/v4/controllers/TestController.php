<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use api\common\models\LoginForm;
use yii\web\Response;
use api\common\models\member;
use Yii;
use crazyfd\qiniu\Qiniu;
class TestController extends Controller
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

    protected function verbs(){
        return [
            'sign'=>['POST'],
            'send'=>['POST'],
            'login'=>['POST'],
            'forget'=>['POST'],
        ];
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