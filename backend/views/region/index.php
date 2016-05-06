<?php
//$this->params['params'] = $label;
//var_dump($this);
?>
<link href="/css/city-picker.css" rel="stylesheet">

    <div style="position: relative;min-width: 330px;">
        <input id="city-picker" class="form-control" type="text" value="" data-toggle="city-picker">
    </div>


<?php
$js = <<<JS
    var city = $('#city-picker');
       $('#city-picker').citypicker({
        province: '江苏省',
        city: '常州市',
        district: '溧阳市'
    });
        
JS;

$this->registerJS($js);
?>


