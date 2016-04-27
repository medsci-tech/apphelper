<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/28
 * Time: 12:00
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\hospital */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['action' => ['hospital/create'],'method'=>'post',]); ; ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'id'=>'name']) ?>

    <?= $this->render('/region/index', [
        'model' => new \common\models\Region,
        'm' => 'Hospital',
        'form' => $form,
    ]);
    ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true,'id'=>'address']) ?>
    <input type="hidden" id="id">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
