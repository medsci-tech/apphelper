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



<?php $form = ActiveForm::begin(['action' => ['ad/create'], 'method' => 'post', 'id' => 'tableForm']); ?>

<div class="modal-body">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">资源类型</label>
        <?= Html::dropDownList('attr_type', 0, [0 => '内部资源', 1 => '外部链接'], ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'attr_id')->textInput(['maxlength' => true]) ?>
        <button type="button" class="btn btn-primary">选择资源</button>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'imgurl')->textInput(['maxlength' => true]) ?>
        <button type="button" class="btn btn-primary">上传图片</button>
    </div>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([0 => '禁用', 1 => '启用']) ?>
</div>
<div class="modal-footer">
    <input type="hidden" name="attr_from" id="attr_from">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>


