<?php
//var_dump($model->formName());
$mLower = strtolower($m);
$parentBom = isset($parentBomId) ? '#' . $parentBomId . ' ' : '';
?>
<link href="/css/city-picker.css" rel="stylesheet">
<div style="position: relative;min-width: 330px;">
    <input id="city-picker" class="form-control" type="text" value="" data-toggle="city-picker">
</div>
<?= $form->field($model, 'province_id')->input('hidden')->label(false) ?>
<?= $form->field($model, 'city_id')->input('hidden')->label(false) ?>
<?= $form->field($model, 'area_id')->input('hidden')->label(false) ?>
<?= $form->field($model, 'province')->input('hidden')->label(false) ?>
<?= $form->field($model, 'city')->input('hidden')->label(false) ?>
<?= $form->field($model, 'area')->input('hidden')->label(false) ?>

<?php
$js = <<<JS
     function regionDefaultValue() {
         var cityTitle = $('$parentBom#city-picker').next().find('.title');
         var province = cityTitle.find('span[data-count="province"]');
         var city = cityTitle.find('span[data-count="city"]');
         var area = cityTitle.find('span[data-count="district"]');

         $('$parentBom#$mLower-province_id').val(province.attr('data-code'));
         $('$parentBom#$mLower-city_id').val(city.attr('data-code'));
         $('$parentBom#$mLower-area_id').val(area.attr('data-code'));
         $('$parentBom#$mLower-province').val(province.text());
         $('$parentBom#$mLower-city').val(city.text());
         $('$parentBom#$mLower-area').val(area.text());
         // console.log($('$parentBom#$mLower-area').val());
     }
JS;
$this->registerJs($js);
?>
