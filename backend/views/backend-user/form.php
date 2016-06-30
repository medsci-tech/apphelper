<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/28
 * Time: 11:07
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\hospital */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['action' => ['backend-user/create'],'method'=>'post','id'=>'tableForm']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'id' => 'userFormName']) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'id' => 'userFormMail']) ?>
        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'id' => 'userFormAddress']) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <?= Html::a('保存','javascript:;', ['class' => 'btn btn-primary', 'id'=>'userFormSubmit']) ?>
    </div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
$('#myModal #userFormSubmit').click(function() {
    var data = {};
    var elmeParent = '#myModal';
    data.username = $(elmeParent + ' #userFormName').val();
    data.email = $(elmeParent + ' #userFormMail').val();
    data.address = $(elmeParent + ' #userFormAddress').val();
    var action = $(elmeParent + ' #tableForm').attr('action');
    var href = window.location.href;
    console.log(data);
    subActionAjaxForMime('post', action, {'User':data}, href);
});
JS;
$this->registerJs($js);
?>