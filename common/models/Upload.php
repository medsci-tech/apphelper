<?php

namespace common\models;

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
    public $fileName;

    public function rules()
    {
        return [
            [['fileName'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload()
    {
        $fileName = date('YmdHis').'.xls';
        if ($this->validate()) {
            $this->fileName->saveAs($fileName);
            return $fileName;
        } else {
            return false;
        }
    }
}