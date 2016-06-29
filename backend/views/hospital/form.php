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
<?php $form = ActiveForm::begin(['action' => ['hospital/create'],'method'=>'post','id'=>'tableForm']); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'id'=>'hospitalName']) ?>

    <div class="form-group">
        <label class="control-label">地区</label>
        <?= $this->render('/region/index');?>
    </div>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::a('保存','javascript:;', ['class' => 'btn btn-primary', 'id'=>'hospitalFormSubmit']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $('#myModal #hospitalFormSubmit').click(function() {
        getRegionValue('Hospital','tableForm');/*地区联动*/
        var data = getRegionReturnValue('myModal');
        var elmeParent = '#myModal';
        data.name = $(elmeParent + ' #hospitalName').val();
        data.address = $(elmeParent + ' #hospital-address').val();
        data.status = $(elmeParent + ' #hospital-status').val();
        var action = $(elmeParent + ' #tableForm').attr('action');
        var href = window.location.href;
        console.log(action);
        subActionAjaxForMime('post', action, {'Hospital':data}, href);
    });
JS;
$this->registerJs($js);
?>