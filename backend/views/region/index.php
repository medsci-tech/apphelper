<?php
use yii\widgets\ActiveForm;
//$model = \common\models\Region::className();
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php //$form = ActiveForm::begin(['enableClientValidation' => false]);?>
<?= $form->field($model,'province', ['options' => ['class' => 'form-group col-lg-2']])->dropDownList($model->getRegionList(0),
    [
        'prompt'=>'--请选择省--',
        'name'=>$m."['province']",
        'onchange'=>'
            $(".form-group.field-region-area").hide();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?typeid=1&pid="+$(this).val(),function(data){
                $("select#region-city").html(data);
            });',
    ])->label('省份', ['class' => 'sr-only']); ?>

<?= $form->field($model, 'city', ['options' => ['class' => 'form-group col-lg-2']])->dropDownList($model->getRegionList($model->province),
    [
        'prompt'=>'--请选择市--',
        'name'=>$m."['city']",
        'onchange'=>'
            $(".form-group.field-region-area").show();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?typeid=2&pid="+$(this).val(),function(data){
                $("select#region-area").html(data);
            });',
    ])->label('市', ['class' => 'sr-only']);  ?>
<?= $form->field($model, 'area', ['options' => ['class' => 'form-group col-lg-2']])->dropDownList($model->getRegionList($model->city),
    [
        'prompt'=>'--请选择区--',
        'name'=>$m."['area']",
    ])->label('区/县',['class' => 'sr-only']);  ?>
<?php //ActiveForm::end();?>