<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */


?>
<div class="article-update">

    <?= $this->render('_form') ?>

</div>
<?php
$js = <<<JS

//提交操作
$('#updateModal #memberFormSubmit').click(function() {
    var data = getRegionReturnValue('updateModal');
    var elmeParent = '#updateModal';
    data.real_name = $(elmeParent + ' #member-real_name').val();
    data.sex = $(elmeParent + ' #member-sex').val();
    data.username = $(elmeParent + ' #member-username').val();
    data.email = $(elmeParent + ' #member-email').val();
    data.hospital_id = $(elmeParent + ' #member-hospital_id').val();
    data.rank_id = $(elmeParent + ' #member-rank_id').val();
    data.status = $(elmeParent + ' #member-status').val();
    var action = $(elmeParent + ' #tableForm').attr('action');
    var href = window.location.href;
    console.log(href);
    subActionAjaxForMime('post', action, {'Member':data}, href);
});
//根据地区筛选医院
$('#updateModal .district').on('click','a',function() {
    var data = getRegionReturnValue('updateModal');
   data.area_id = $(this).attr('data-code');
    console.log(data);
    var action = '/hospital/get-hospital-by-region';
    var hospitalDom = '#updateModal #member-hospital_id';
    getHospitalByRegionForMime('post', action, data, hospitalDom);
});

JS;
$this->registerJs($js);
?>