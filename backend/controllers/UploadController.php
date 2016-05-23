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
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        $qiNiuSet = Yii::$app->params['qiniu'];
        if($uploadModel->file){
            if($uploadModel->file->size > 2097152){
                $return = ['code'=>801,'msg'=>'文件不能超过2M','data'=>''];
            }else{
                $result = $uploadModel->image(Yii::getAlias('@webroot/uploads'));
                if(200 == $result['code']){
                    $qiniu = new Qiniu($qiNiuSet['accessKey'], $qiNiuSet['secretKey'],$qiNiuSet['domain'], $qiNiuSet['bucket']);
                    $key = 'images/exam/' . $result['data']['name']; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
                    $qiniu->uploadFile($result['data']['path'].$result['data']['name'],$key); // 要上传的图片
                    $url = $qiniu->getLink($key);
                    $return = ['code'=>200,'msg'=>'上传成功','data'=>[
                        'tName' => $uploadModel->file->name,
                        'saveName' => $url,
                    ]];
                }else{
                    $return = ['code'=>802,'msg'=>$result['msg'],'data'=>''];
                }
            }
        }else{
            $return = ['code'=>803,'msg'=>'上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

}
