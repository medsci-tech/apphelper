<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?=
     $this->render('/region/index', [
        'model' => new \common\models\Region,
        'm' => 'Member',
        'form' => $form,
    ]);
    ?>

    <?= $form->field($model, 'hospital_id')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->column()) ?>
    <?= $form->field($model, 'rank_id')->dropDownList(Yii::$app->params['member']['rank']) ?>

    <?= $form->field($model, 'status')->dropDownList([0 => '禁用', 1 => '正常']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
