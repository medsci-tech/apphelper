<?php
//var_dump($model->formName());
?>
<link href="/css/city-picker.css" rel="stylesheet">
<div style="position: relative;min-width: 330px;">
    <input id="city-picker" class="form-control" type="text" value="" data-toggle="city-picker">
</div>


<?php
//$js = <<<JS
    // function regionDefaultValue(params) {
    // var pickerHtml = '<span class="select-item" data-count="province" data-code="' +params.provinceCode+ '">' +params.provinceName+ '</span>/' +
    //     '<span class="select-item" data-count="city" data-code="' +params.cityCode+ '">' +params.cityName+ '</span>/' +
    //     '<span class="select-item" data-count="district" data-code="' +params.areaCode+ '">' +params.areaName+ '</span>';
    //     var pickerspan = $('#city-picker').next();
    //     var selectcontent = pickerspan.next().find('.city-select-content');
    //     var cityObj = ChineseDistricts[params.provinceCode];
    //     var areaObj = ChineseDistricts[params.cityCode];
    //     var cityHtml = '',areaHtml = '',activeClass = '';
    //     /*遍历市*/
    //     $.each(cityObj, function(i, n){
    //         if(params.cityCode == i){
    //             activeClass = 'active';
    //         }else {
    //             activeClass = '';
    //         }
    //         cityHtml += '<a title="' + n +'" data-code="' + i +'" class="' + activeClass + '">' + n +'</a>';
    //     });
    //     /*遍历区*/
    //     $.each(areaObj, function(i, n){
    //         if(params.areaCode == i){
    //             activeClass = 'active';
    //         }else {
    //             activeClass = '';
    //         }
    //         areaHtml += '<a title="' + n +'" data-code="' + i +'" class="' + activeClass + '">' + n +'</a>';
    //     });
    //    
    //     pickerspan.find('.placeholder').css('display','none');
    //     pickerspan.find('.title').css('display','inline').html(pickerHtml);
    //     selectcontent.find('.province a[data-code="'+params.provinceCode+'"]').addClass('active');
    //     selectcontent.find('[data-count="city"]').find('dd').html(cityHtml);
    //     selectcontent.find('[data-count="district"]').find('dd').html(areaHtml);
    //    
    //     console.log(areaHtml);
    // }
    //  var regionSelect = {
    //     provinceCode:'440000',
    //     cityCode:'440300',
    //     areaCode:'440305',
    //     provinceName:'广东省',
    //     cityName:'深圳市',
    //     areaName:'南山区',
    // }
    // regionDefaultValue(regionSelect);
//JS;
//$this->registerJs($js);
?>
