<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/22
 * Time: 14:27
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="hospital-search">

    <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= Html::button('查询', ['id'=>'btn_search','class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::button('添加单位', ['id'=>'btn_add','class' => 'btn btn-success','data-toggle'=>'modal','data-target'=>"#myModal"]) ?>

    <?php ActiveForm::end(); ?>
</div>