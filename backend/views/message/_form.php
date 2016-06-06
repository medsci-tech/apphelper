<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 14:48
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['action' => ['message/create'],'method'=>'post','id'=>'tableForm']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <label class="control-label">推送范围：
                <label class="checkbox-inline">
                    <input type="radio" name="type" id="rdo1" class="radioItem"
                           value="1" checked> 全部用户
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="type" id="rdo2" class="radioItem"
                           value="0"> 指定用户
                </label>
            </label>
        </div>

        <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'id')->input('hidden')->label(false) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">保存，稍后发送</button>
        <?= Html::a('发送','javascript:;', ['class' => 'btn btn-primary', 'id'=>'hospitalFormSubmit']) ?>
    </div>
<?php ActiveForm::end(); ?>