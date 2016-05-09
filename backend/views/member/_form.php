<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modal-body">

    <?php
    $form = ActiveForm::begin([
    'action' => ['create'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
    ]); ?>
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">地区</label>
        <?= $this->render('/region/index');?>
        <?= $form->field($model, 'province_id')->input('hidden')->label(false) ?>
        <?= $form->field($model, 'city_id')->input('hidden')->label(false) ?>
        <?= $form->field($model, 'area_id')->input('hidden')->label(false) ?>
        <?= $form->field($model, 'province')->input('hidden')->label(false) ?>
        <?= $form->field($model, 'city')->input('hidden')->label(false) ?>
        <?= $form->field($model, 'area')->input('hidden')->label(false) ?>
    </div>

    <?= $form->field($model, 'hospital_id')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->column()) ?>
    <?= $form->field($model, 'rank_id')->dropDownList(Yii::$app->params['member']['rank']) ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

    <div class="form-group">
        <?= Html::a('确定','javascript:;', ['class' => 'btn btn-primary', 'id' => 'memberFormSubmit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
    $('#updateModal #memberFormSubmit').click(function() {
        var cityTitle = $('#updateModal #city-picker').next().find('.title');
        var province = cityTitle.find('span[data-count="province"]');
        var city = cityTitle.find('span[data-count="city"]');
        var area = cityTitle.find('span[data-count="district"]');
        
        $('#updateModal #member-province_id').val(province.attr('data-code'));
        $('#updateModal #member-city_id').val(city.attr('data-code'));
        $('#updateModal #member-area_id').val(area.attr('data-code'));
        
        $('#updateModal #member-province').val(province.text());
        $('#updateModal #member-city').val(city.text());
        $('#updateModal #member-area').val(area.text());
        
        $('#updateModal #tableForm').submit();
    });
JS;
$this->registerJs($js);
?>