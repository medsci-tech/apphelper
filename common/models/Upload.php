<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class Upload extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    /**
     * excel表格上传
     * @param $uploadPath
     * @return array
     */
    public function excel($uploadPath)
    {
        $suffix = mb_substr($this->file->name, (mb_strripos($this->file->name, '.') + 1));
        if(in_array($suffix,['xls','xlsx'])){
            $fileName = $uploadPath .'/temp/'. date('YmdHis') . '.' . $suffix;
            $res = $this->file->saveAs($fileName);
            if($res){
                $return = ['code'=>200,'msg'=>'success','data'=>$fileName];
            }else{
                $return = ['code'=>701,'msg'=>'上传失败哦','data'=>''];
            }
        }else{
            $return = ['code'=>703,'msg'=>'请上传Excel格式文件','data'=>''];
        }
        return $return;
    }

    /**
     * 图片上传
     * @param $uploadPath
     * @return array
     */
    public function image($uploadPath){
        $name = date('YmdHis') . rand(1000,9999);
        $suffix = mb_substr($this->file->name, (mb_strripos($this->file->name, '.') + 1));
        if(in_array($suffix,['png', 'jpg', 'gif'])){
            $filePath = $uploadPath .'/examImg/';
            if(!file_exists($filePath)){
                @mkdir($filePath);
                @touch($filePath . 'index.html');
            }
            $fileName = $filePath . $name . '.' . $suffix;
            $res = $this->file->saveAs($fileName);
            if($res){
                $return = ['code'=>200,'msg'=>'上传成功哦','data'=>[
                    'path' => $filePath,
                    'name' => $name . '.' . $suffix,
                ]];
            }else{
                $return = ['code'=>701,'msg'=>'上传失败哦','data'=>''];
            }
        }else{
            $return = ['code'=>703,'msg'=>'目前只支持png,jpg,gif格式','data'=>''];
        }
        return $return;
    }
}