<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="modal-body">
        <div id="member-import" class="form-group">
            <label class="control-label">Excel文件</label>
            <?= $this->render('/webuploader/index',[
                'actionCtrl' => 'import',
                'imgMaxSize' => 20971520,/*文件限制20M*/
            ]);?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <?= Html::button('确定', ['class' => 'btn btn-primary', 'id' => 'memberFormSubmit']) ?>
    </div>


<?php
$js = <<<JS

$('#importModal #memberFormSubmit').click(function() {
    var excelFile = $('[data-toggle="upload-saveInput"]').val();
    var data = {
        'excel' : excelFile
    }
    subActionAjaxForMime('post', 'upexcel', data, 'index');
});

JS;
$this->registerJs($js);
?>