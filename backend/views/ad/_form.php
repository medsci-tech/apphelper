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

<div class="ad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= Html::dropDownList('attr_type',[0 => '内部资源', 1 => '外部链接'])  ?>

    <?= $form->field($model, 'attr_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imgurl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([0 => '禁用', 1 => '启用']) ?>

    <div class="form-group">
        <input type="hidden" name="attr_from" id="attr_from">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
