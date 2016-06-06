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
                           value="1"> 全部用户
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="type" id="rdo2" class="radioItem"
                           value="0" checked> 指定用户
                </label>
            </label>
        </div>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
        <?= $form->field($model, 'id')->input('hidden')->label(false) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <?= Html::a('保存','javascript:;', ['class' => 'btn btn-primary', 'id'=>'hospitalFormSubmit']) ?>
    </div>
<?php ActiveForm::end(); ?>