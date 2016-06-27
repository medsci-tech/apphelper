<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-inline navbar-btn">
    <?= Html::a('返回', $referrerUrl ?? 'index', ['class' => 'btn btn-white']) ?>
    <?= Html::button('启用', ['class' => 'btn btn-info','data-toggle'=> 'enable']) ?>
    <?= Html::button('禁用', ['class' => 'btn btn-warning','data-toggle'=> 'disable']) ?>
</div>

<?php
$js = <<<JS
   /*删除*/
    $('[data-toggle="del"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要删除选中的信息吗",
            text: "删除后将无法恢复，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','del');
            swal("已删除！", "您已经永久删除了信息。", "success");
        });
    });
    /*启用*/
    $('[data-toggle="enable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要启用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#23c6c8",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','enable');
            swal("已启用！", "", "success");
        });
    });
    /*禁用*/
    $('[data-toggle="disable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要禁用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','disable');
            swal("已禁用！", "", "success");
        });
    });
    
JS;
$this->registerJs($js);
?>