<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'options' => ['class' => 'form-inline','enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'real_name') ?>
    <?= $form->field($model, 'hospital_id')->dropDownList(array_flip(array_merge(['全部' => ''], array_flip(\common\models\Hospital::find()->select('name')->indexBy('id')->column())))) ?>

    <?= Html::button('添加用户', ['class' => 'btn btn-success animation_select','data-toggle'=>'modal','data-target'=>'#myModal']) ?>
    <?= FileUpload::widget([
        'model' => $uploadModel,
        'attribute' => 'file',
        'url' => ['index'],
    ]);?>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::a('导出','export', ['class' => 'btn btn-info']) ?>
    <?= Html::a('启用', 'javascript:void(0);', ['class' => 'btn btn-primary','id'=> 'enable']) ?>
    <?= Html::a('禁用', 'javascript:void(0);', ['class' => 'btn btn-warning','id'=> 'disable']) ?>
    <?= Html::a('批量删除', 'javascript:void(0);', [
        'class' => 'btn btn-danger',
        'id'=> 'del',
    ]) ?>
    <?php ActiveForm::end(); ?>

<?php
$js = <<<JS
  /*删除*/
  $('#del').click(function() {
    swal({
        title: "您确定要删除选中的信息吗",
        text: "删除后将无法恢复，请谨慎操作！",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: '取消',
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "删除",
        closeOnConfirm: false
    }, function () {
        subActionForMamber('typeForm','del');
        swal("删除成功！", "您已经永久删除了信息。", "success");
    });
  });
  /*禁用*/
   $('#disable').click(function() {
       swal({
            title: "您确定要禁用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "禁用",
            closeOnConfirm: false
        }, function () {
            subActionForMamber('typeForm','disable');
            swal("禁用成功！", "", "success");
        });
  });
  /*启用*/
   $('#enable').click(function() {
        swal({
            title: "您确定要启用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#1ab394",
            confirmButtonText: "启用",
            closeOnConfirm: false
        }, function () {
            subActionForMamber('typeForm','enable');
            swal("启用成功！", "", "success");
        });
  });
    function subActionForMamber(formId,val) {
        $('#' + formId).val(val);
        $('#'+formId).submit();
    }
JS;
$this->registerJs($js);
?>


</div>