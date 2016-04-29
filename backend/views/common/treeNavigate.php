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
     var e = [{
        text: "父节点 1",
        nodes: [
            {
                text: "子节点 1",
                nodes: [
                    {
                        text: "孙子节点 1"
                    },
                    {
                        text: "孙子节点 2"
                    }
                ]
            }, 
            {
                text: "子节点 2"
            }
        ]
    }];
    $("#treeview2").treeview({
        levels: 1,
        data: e
    });
});
JS;
$this->registerJs($js);
?>