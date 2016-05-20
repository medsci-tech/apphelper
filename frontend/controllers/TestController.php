<?php

namespace frontend\controllers;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
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
        $accessKey ='OL3qoivVQhxkRWAL_W3CRs435m1Y5CeJVfkKIDg-';
        $secretKey = 'mPEylNDXx64U84HjkEcUwJyXg1B40-GUUfC_TR8T';
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 要上传的空间
        $bucket = 'apphelper-images';

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        $filePath = './image/1.jpg';

        // 上传到七牛后保存的文件名
        $key = 'my-php-logo.png';

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
        
//        $qiniu = new Qiniu('', '','http://wap.dev', 'Bucket_Name');
//        $key = time();
//        $qiniu->uploadFile('./image/1.jpg',$key);
//        $url = $qiniu->getLink($key);
//        echo($url);

    }
}
