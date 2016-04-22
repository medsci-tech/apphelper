<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'options' => ['class' => 'form-inline','enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'real_name') ?>
    <?= $form->field($model, 'hospital_id')->dropDownList(array_flip(array_merge(['全部' => ''], array_flip(\common\models\Hospital::find()->select('name')->indexBy('id')->column())))) ?>

    <?= Html::a('添加用户', ['create'], ['class' => 'btn btn-success animation_select','data-animation'=>'fadeInDown']) ?>
    <?= Html::a('导出','export', ['class' => 'btn btn-info']) ?>
    <?= FileUpload::widget([
        'model' => $uploadModel,
        'attribute' => 'file',
        'url' => ['index'],
    ]);?>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?php ActiveForm::end(); ?>




</div>