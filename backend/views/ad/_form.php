<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 17:07
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>



<?php $form = ActiveForm::begin(['action' => ['ad/create'], 'method' => 'post', 'id' => 'tableForm']); ?>

<div class="modal-body">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">资源类型</label>
        <?= Html::dropDownList('attr_type', 0, [0 => '内部资源', 1 => '外部链接'], ['id'=>'attr_type','class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <div class="form-group">
            <label class="control-label">资源地址</label>
            <input type="text" name="attr_name" id="attr_name" class="form-control">
        </div>
        <button type="button" data-toggle="modal" class="btn btn-primary" id="select">选择资源</button>
    </div>
    <div class="form-group">
        <label class="control-label">图片地址</label>
        <?= $this->render('/webuploader/index',[
            'name' => 'AD[imgurl]',
            'imgMaxSize' => 2097152,/*文件限制2M*/
            'uploadPath' => 'image/advertisement'
        ]);?>
        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <input type="hidden" name="aid" id="aid">
    <input type="hidden" name="mode" id="mode">
    <input type="hidden" name="attr_id" id="attr_id">
    <input type="hidden" name="attr_from" id="attr_from">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $('#attr_type').change(function(){
        var valOptions= $("#attr_type  option:selected").val();
        console.log(valOptions);
        if(valOptions == '0') {
            $('#select').show();
            $('#attr_name').val('');
        } else {
            $('#select').hide();
            $('#attr_name').val('');
        }
    });

JS;
$this->registerJs($js);
?>


