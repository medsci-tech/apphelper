<?php
namespace backend\controllers;

use common\models\Upload;
use yii\web\UploadedFile;
use Yii;
class UploadController extends BackendController
{

    /**
     * 图片上传
     */
    public function actionImg(){
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        if($uploadModel->file){
            if($uploadModel->file->size > 2097152){
                $return = ['code'=>801,'msg'=>'文件不能超过2M','data'=>''];
            }else{
                $return = $uploadModel->image(Yii::getAlias('@webroot/uploads'));
            }
        }else{
            $return = ['code'=>802,'msg'=>'上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

}
