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

    /**
     * excel文件导入到本地
     */
    public function actionImport(){
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        if($uploadModel->file){
            $result = $uploadModel->excel(Yii::getAlias('@webroot/uploads'));
            if(200 == $result['code']){
                $return = ['code'=>200,'msg'=>'上传成功','data'=>[
                    'tName' => $uploadModel->file->name,
                    'saveName' => $result['data'],
                ]];
            }else{
                $return = ['code'=>802,'msg'=>$result['msg'],'data'=>''];
            }
        }else{
            $return = ['code'=>803,'msg'=>'上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

    public function actionPdf(){
        $uploadModel = new Upload();
        $qiniuPath = Yii::$app->request->get()['path'];
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        if($uploadModel->file){
            $result = $uploadModel->pdf(Yii::getAlias('@webroot/uploads'));
            if(200 == $result['code']){
                $pdf2png = self::pdf2png($result['data'], $qiniuPath);
                if($pdf2png){
                    $return = ['code'=>200,'msg'=>'上传成功','data'=>$pdf2png];
                }else{
                    $return = ['code'=>801,'msg'=>'pdf 转 图片失败','data'=>''];
                }
            }else{
                $return = ['code'=>802,'msg'=>$result['msg'],'data'=>''];
            }
        }else{
            $return = ['code'=>803,'msg'=>'上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

    public function pdf2png($PDF, $qiniuPath){
        if(!extension_loaded('imagick')){
            return false;
        }
        if(!file_exists($PDF)){
            echo'缺少pdf文件';
            return false;
        }
        $qiNiuSet = Yii::$app->params['qiniu'];
        $qiniu = new Qiniu($qiNiuSet['accessKey'], $qiNiuSet['secretKey'],$qiNiuSet['domain'], $qiNiuSet['bucket']);
        $IM =new \imagick();
        $IM->setResolution(120,120);
        $IM->setCompressionQuality(100);
        $IM->readImage($PDF);
        $Return = [];
        foreach($IM as $Key => $Var){
            $Var->setImageFormat('png');
            $saveName = date('YmdHis') . rand(1000,9999) .'.png';
            $Filename = '/uploads/temp/' . $saveName;
            if($Var->writeImage($Filename)==true){
                $key = $qiniuPath . '/' . $saveName; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
                $qiniu->uploadFile($PDF,$key); // 要上传的图片
                $url = $qiniu->getLink($key);
                if($url){
                    $Return[]= $url;
                }
            }
        }
        $Return = array_unique($Return);
        sort($Return);
        return $Return;
    }

}
