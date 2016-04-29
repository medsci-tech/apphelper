<?php
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>


<!--树形视图--start-->
<div id="treeview2" class="col-lg-2 modal-body treeview">

</div>
<!--树形视图--end-->

<?php
$js = <<<JS
$(function() {
    $("#treeview2").treeview({
        levels: 1,
        data: $examClass
    });
});
JS;
$this->registerJs($js);
?>