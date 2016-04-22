<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
//$model = \common\models\Region::className();
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$htmlClass = 'form-group container-fluid';
?>
<div class="form-inline form-group">
<?php //$form = ActiveForm::begin(['enableClientValidation' => false]);?>
<?= $form->field($model,'province_id', ['options' => ['class' => $htmlClass]])->dropDownList($model->getRegionList(0,1),
    [
        'prompt'=>'--请选择省--',
        'name'=>$m."[province_id]",
        'onchange'=>'
            $(".form-group.field-region-area_id").hide();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?grade=2&pid="+$(this).val(),function(data){
                $("select#region-city_id").html(data);
                $("select#region-province_id").val($("#province_id").val());
                $("select#region-city_id").val($("#city_id").val());
                $("select#region-city_id").trigger("change");
            });',
    ])->label('省份', ['class' => 'sr-only']); ?>

<?= $form->field($model, 'city_id', ['options' => ['class' => $htmlClass]])->dropDownList($model->getRegionList($model->province_id, 2),
    [
        'prompt'=>'--请选择市--',
        'name'=>$m."[city_id]",
        'onchange'=>'
            $(".form-group.field-region-area_id").show();
            $.post("'.yii::$app->urlManager->createUrl('region/list').'?grade=3&pid="+$(this).val(),function(data){
                $("select#region-area_id").html(data);
                $("select#region-area_id").val($("#area_id").val());
                //$("select#region-city_id").trigger("change");
            });',
    ])->label('市', ['class' => 'sr-only']);  ?>
<?= $form->field($model, 'area_id', ['options' => ['class' => $htmlClass]])->dropDownList($model->getRegionList($model->city_id,3),
    [
        'prompt'=>'--请选择区--',
        'name'=>$m."[area_id]",
    ])->label('区/县',['class' => 'sr-only']);  ?>
    <div style="clear: both;margin: 0;padding: 0;width: 0;height: 0;"></div>
</div>
<?php //ActiveForm::end();?>
<?php
$js=<<<JS
 $('.del').click(function () {

});
JS;
$this->registerJs($js);
?>
