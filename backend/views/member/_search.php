<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-search">

    <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'real_name') ?>
    <?= $form->field($model, 'hospital_id')->dropDownList(array_flip(array_merge(['全部' => ''], array_flip(\common\models\Hospital::find()->select('name')->indexBy('id')->column())))) ?>

    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::a('导出','export', ['class' => 'btn btn-info']) ?>
    <?= Html::a('添加用户', ['create'], ['class' => 'btn btn-success animation_select','data-animation'=>'fadeInDown']) ?>

    <?php ActiveForm::end(); ?>


    <?php $form = ActiveForm::begin([
        'action' => ['import'],
        'options' => ['enctype' => 'multipart/form-data']
    ]) ?>
    <?= $form->field($uploadModel, 'file')->fileInput() ?>
    <?= Html::submitButton('查询', ['class' => 'btn btn-info']) ?>
    <?php ActiveForm::end() ?>

    <div class="error-summary hide"><ul></ul></div>
</div>