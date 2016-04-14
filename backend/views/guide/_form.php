<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/12
 * Time: 14:29
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\guide */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="guide-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->widget('yidashi\webuploader\Webuploader') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>