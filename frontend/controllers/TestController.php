<?php

namespace frontend\controllers;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
//use Qiniu\Auth;
// 引入上传类
//use Qiniu\Storage\UploadManager;
use crazyfd\qiniu\Qiniu;
/**
 * ArticleController implements the CRUD actions for Article model.
 */
class TestController extends Controller
{
    public $layout =false;

    /**
     *
     */
    public function actionIndex()
    {
//        $qiniu = new Qiniu('OL3qoivVQhxkRWAL_W3CRs435m1Y5CeJVfkKIDg-', 'mPEylNDXx64U84HjkEcUwJyXg1B40-GUUfC_TR8T','http://api.dev', $bucket);
//        $key = time();
//        $qiniu->uploadFile($_FILES['tmp_name'],$key);
//        $url = $qiniu->getLink($key);
        return $this->render('index', [

        ]);
    }
    public function actionUpload()
    {
        $accessKey =Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];      
        $bucket = Yii::$app->params['qiniu']['bucket']; // 要上传的空间
        $domain = Yii::$app->params['qiniu']['domain']; // 七牛返回的域名
        // 构建鉴权对象
        //$auth = new Auth($accessKey, $secretKey);



//        // 生成上传 Token
//        $token = $auth->uploadToken($bucket);
//
//        // 要上传文件的本地路径
//        $filePath = './image/1.jpg';
//
//        // 上传到七牛后保存的文件名
//        $key = 'image/ad/my-php-logo.png';
//
//        // 初始化 UploadManager 对象并进行文件的上传。
//        $uploadMgr = new UploadManager();
//
//        // 调用 UploadManager 的 putFile 方法进行文件的上传。
//        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//     print_r($ret);
        
        
        $qiniu = new Qiniu($accessKey, $secretKey,$domain, $bucket);
        $key = 'images/ad/'.time().'.jpg'; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
        $qiniu->uploadFile('./image/1.png',$key); // 要上传的图片
        $url = $qiniu->getLink($key);
        echo($url);

    }
}
