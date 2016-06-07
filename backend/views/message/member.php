<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/7
 * Time: 9:36
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

backend\assets\AppAsset::register($this);
?>

<?php $form = ActiveForm::begin(['action' => ['ad/find'], 'method' => 'get', 'id' => 'resourceForm']); ?>
    <div class="modal-body" style="background-color: white">
        <textarea name="phone" cols=40 rows=10>
        </textarea>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="type" id="type">
        <input type="hidden" name="attr_id" id="attr_id">
        <input type="hidden" name="attr_name" id="attr_name">
        <button id="btnClose" type="button" class="btn btn-white">关闭</button>
        <button id="btnConfirm" type="button" class="btn btn-primary">确定</button>
    </div>


<?php ActiveForm::end(); ?>

<?php
$js = <<<JS

    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

    $("#btnClose").click(function() {
        parent.layer.close(index);
    });

    $("#btnConfirm").click(function() {
        parent.$('#attr_name').val($("#attr_name").val());
        parent.$('#attr_id').val($("#attr_id").val());
        parent.$('#attr_from').val($("#type").val());
        parent.layer.close(index);
    });

    $("#btnSearch").click(function() {
        $("#resourceForm").submit();
    });
JS;
$this->registerJs($js);
?>