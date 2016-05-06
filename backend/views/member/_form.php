<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
?>

<div class="modal-body">

    <?php
    $form = ActiveForm::begin([
    'action' => ['create'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
    ]); ?>
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <div class="form-group field-region">
        <label class="control-label" for="region">地区</label>
    <?=
     $this->render('/region/index', [
        'model' => new \common\models\Region,
        'm' => 'Member',
        'form' => $form,
    ]);
    ?>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'hospital_id')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->column()) ?>
    <?= $form->field($model, 'rank_id')->dropDownList(Yii::$app->params['member']['rank']) ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

    <div class="form-group">
        <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
