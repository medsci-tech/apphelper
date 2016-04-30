<?php
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>


<!--树形视图--start-->
<div id="treeView" class="col-lg-2 modal-body"></div>
<!--树形视图--end-->

<?php
$js = <<<JS
$(function() {
    $("#treeView").treeview({
        levels: 9,
        data: $examClass
    });
});
JS;
$this->registerJs($js);
?>