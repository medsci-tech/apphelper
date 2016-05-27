<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $directoryStructureData  */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
?>
<?php
$form = ActiveForm::begin([
    'action' => ['form'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'rid')->dropDownList($directoryStructureData) ?>
    <?= $form->field($model, 'author')->textInput() ?>
    <?= $form->field($model, 'rids')->textInput() ?>
    <?= $form->field($model, 'keyword')->textInput(['placeholder' => '关键词可添加多个，用“|”分开']) ?>
    <?= $form->field($model, 'imgurl')->textInput() ?>
    <?= $form->field($model, 'content')->textarea() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

