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
            if ($this->validate()) {
                $res = $this->file->saveAs($fileName);
                if($res){
                    $return = ['code'=>200,'msg'=>'success','data'=>$fileName];
                }else{
                    $return = ['code'=>701,'msg'=>'save error','data'=>''];
                }
            } else {
                $return = ['code'=>702,'msg'=>'validate error','data'=>''];
            }
        }else{
            $return = ['code'=>703,'msg'=>'suffix error','data'=>''];
        }
        return $return;
    }

    /**
     * 图片上传
     * @param $uploadPath
     * @return array
     */
    public function image($uploadPath){
        $month = date('Ym');
        $second = date('YmdHis');
        $suffix = mb_substr($this->file->name, (mb_strripos($this->file->name, '.') + 1));
        if(in_array($suffix,['png', 'jpg', 'gif'])){
            $filePath = $uploadPath .'/examImg/' . $month .'/';
            if(!file_exists($filePath)){
                @mkdir($filePath);
                @touch($filePath . 'index.html');
            }
            $fileName = $filePath . $second . '.' . $suffix;
            if ($this->validate()) {
                $res = $this->file->saveAs($fileName);
                if($res){
                    $return = ['code'=>200,'msg'=>'上传成功哦','data'=>['tName' => $this->file->name, 'saveName' => '/uploads/examImg/' . $month . '/' . $second . '.' . $suffix]];
                }else{
                    $return = ['code'=>701,'msg'=>'上传失败哦','data'=>''];
                }
            } else {
                $return = ['code'=>702,'msg'=>'上传失败哦','data'=>''];
            }
        }else{
            $return = ['code'=>703,'msg'=>'目前只支持png,jpg,gif格式','data'=>''];
        }
        return $return;
    }
}