<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false]);?>

<?= $form->field($model,'province')->dropDownList($model->getRegionList(0),
    [
        'prompt'=>'--请选择省--',
        'onchange'=>'
            $(".form-group.field-member-area").hide();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?typeid=1&pid="+$(this).val(),function(data){
                $("select#region-city").html(data);
            });',
    ]) ?>

<?= $form->field($model, 'city')->dropDownList($model->getRegionList($model->province),
    [
        'prompt'=>'--请选择市--',
        'onchange'=>'
            $(".form-group.field-member-area").show();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?typeid=2&pid="+$(this).val(),function(data){
                $("select#region-area").html(data);
            });',
    ]) ?>
<?= $form->field($model, 'area')->dropDownList($model->getRegionList($model->city),['prompt'=>'--请选择区--',]) ?>
<?php ActiveForm::end();?>