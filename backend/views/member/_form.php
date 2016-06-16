<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
    'action' => ['create'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">地区</label>
        <?= $this->render('/region/index');?>
    </div>

    <?= $form->field($model, 'hospital_id')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->column()) ?>
    <?= $form->field($model, 'rank_id')->dropDownList(Yii::$app->params['member']['rank']) ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::button('确定', ['class' => 'btn btn-primary', 'id' => 'memberFormSubmit']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $('#tableForm #memberFormSubmit').click(function() {
        getRegionValue('Member','tableForm');/*地区联动*/
        $('#tableForm').submit();
    });
JS;
$this->registerJs($js);
?>