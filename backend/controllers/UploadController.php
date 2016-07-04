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

    /**
     * 上传pdf
     */
    public function actionPdf(){
        $uploadModel = new Upload();
        $qiniuPath = Yii::$app->request->get()['path'];
        $uploadModel->file = UploadedFile::getInstanceByName('file');
        if($uploadModel->file){
            $result = $uploadModel->pdf(Yii::getAlias('@webroot/uploads'));
            if(200 == $result['code']){
                $pdf2png = self::pdf2png($result['data'], $qiniuPath);
                if(200 == $pdf2png['code']){
                    $return = ['code'=>200,'msg'=>'上传成功','data'=>[
                        'tName' => $uploadModel->file->name,
                        'saveName' => $pdf2png['data'],
                    ]];
                }else{
                    $return = ['code'=>801,'msg'=>$pdf2png['msg'],'data'=>''];
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
     * pdf转png
     * @param $pdfFile
     * @param $qiniuPath
     * @return array
     * @throws \yii\web\HttpException
     */
    public function pdf2png($pdfFile, $qiniuPath){
        $PDF = $pdfFile['path'] . $pdfFile['name'];
        if(!extension_loaded('imagick')){
            $return = ['code'=>601,'msg'=>'缺少扩展','data'=>''];
        }elseif(!file_exists($PDF)){
            $return = ['code'=>602,'msg'=>'文件错误','data'=>''];
        }else{
            $qiNiuSet = Yii::$app->params['qiniu'];
            $qiniu = new Qiniu($qiNiuSet['accessKey'], $qiNiuSet['secretKey'],$qiNiuSet['domain'], $qiNiuSet['bucket']);
            $IM =new \imagick();
            $IM->setResolution(120,120);
            $IM->setCompressionQuality(100);
            $IM->readImage($PDF);
            $returnData = [];
            foreach($IM as $Key => $Var){
                $Var->setImageFormat('png');
                $saveName = date('YmdHis') . rand(1000,9999) .'.png';
                $Filename = $pdfFile['path'] . $saveName;
                if($Var->writeImage($Filename)==true){
                    $key = $qiniuPath . '/' . $saveName; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
                    $qiniu->uploadFile($Filename,$key); // 要上传的图片
                    $url = $qiniu->getLink($key);
                    if($url){
                        $returnData[]= $url;
                    }
                }
            }
            $returnData = array_unique($returnData);
            sort($returnData);
            $return = ['code'=>200,'msg'=>'成功','data'=>$returnData];
        }
        return $return;
    }

}
