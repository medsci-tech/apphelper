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

    public function excel($filePath)
    {
        $suffix = mb_substr($this->file->name, (strripos($this->file->name, '.') + 1));
        if(in_array($suffix,['xls','xlsx'])){
            $fileName = $filePath .'/temp/'. date('YmdHis') . '.' . $suffix;
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
}