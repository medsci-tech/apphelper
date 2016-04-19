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
<?php $form = ActiveForm::begin(); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $this->render('/region/index', [
        'model' => new \common\models\Region,
        'm' => 'Hospital',
        'form' => $form,
    ]);
    ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <!--<div class="form-group"><label>Email</label> <input type="email" placeholder="请输入您的Email" class="form-control"></div>-->
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
    <?php ActiveForm::end(); ?>
