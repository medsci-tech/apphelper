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
                'actionCtrl' => 'videos',
                'uploadPath' => 'video',
                'imgMaxSize' => 20971520,/*文件限制20M*/
            ]);?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <?= Html::button('确定', ['class' => 'btn btn-primary', 'id' => 'formSubmit']) ?>
    </div>


<?php
$js = <<<JS

$('#myModal #formSubmit').click(function() {
    var url = $('[data-toggle="upload-saveInput"]').val();
    var name = $('[data-toggle="upload-progressInput"]').val();
    var data = {
        'name' : name,
        'url' : url
    }
    subActionAjaxForMime('post', 'form', data, 'index');
});

JS;
$this->registerJs($js);
?>