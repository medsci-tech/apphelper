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

    <?= $form->field($model, 'name') ?>
<!---->
<!--    --><?//= $form->field($model, 'title') ?>
<!---->
<!--    --><?//= $form->field($model, 'category_id')->dropDownList(array_merge(['' => '全部'], \common\models\Category::find()->select('title')->indexBy('id')->column())) ?>
<!---->
<!--    --><?php // echo $form->field($model, 'status')->dropDownList(['' => '全部', '待审核', '正常']) ?>

    <?
    echo $this->render('/region/index', [
        'model' => new \common\models\Region,
        'm' => 'Hospital',
        'form' => $form,
    ]);
    ?>

    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>