<?php
namespace backend\controllers;

use common\models\Upload;
use yii\web\UploadedFile;
use Yii;
use crazyfd\qiniu\Qiniu;
class UploadController extends BackendController
{

    /**
     * 图片上传
     */
    public function actionImg(){
        $appYii = Yii::$app;
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        $qiNiuSet = $appYii->params['qiniu'];
        if($uploadModel->file){
            $result = $uploadModel->image(Yii::getAlias('@webroot/uploads'));
            if(200 == $result['code']){
                $uploadPath = $appYii->request->get()['path'];
                $qiniu = new Qiniu($qiNiuSet['accessKey'], $qiNiuSet['secretKey'],$qiNiuSet['domain'], $qiNiuSet['bucket']);
                $key = $uploadPath . '/' . $result['data']['name']; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
                $qiniu->uploadFile($result['data']['path'].$result['data']['name'],$key); // 要上传的图片
                $url = $qiniu->getLink($key);
                if($url){
                    $return = ['code'=>200,'msg'=>'上传成功','data'=>[
                        'tName' => $uploadModel->file->name,
                        'saveName' => $url,
                    ]];
                }else{
                    $return = ['code'=>801,'msg'=>'远程上传失败','data'=>''];
                }
            }else{
                $return = ['code'=>802,'msg'=>$result['msg'],'data'=>''];
            }
        }else{
            $return = ['code'=>803,'msg'=>'上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

}
