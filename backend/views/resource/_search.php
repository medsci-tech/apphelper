<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
/* @var $attr_type */
/* @var $searchAction */
$get = Yii::$app->request->get();
$nameSearch = $get['Resource']['title'] ?? '';
$ridSearch = $get['Resource']['rid'] ?? '';
?>

    <?php
    $form = ActiveForm::begin([
        'action' => [$attr_type ? 'index' : 'pharmacy'],
        'method' => 'get',
        'options' => ['class' => 'form-inline navbar-btn', 'id' => 'search-form'],
    ]); ?>

    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'rid')->hiddenInput()->label(false) ?>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('添加', $attr_type ? 'create' : 'create_pha', ['class' => 'btn btn-success']) ?>
    <?= Html::button('启用', ['class' => 'btn btn-info','data-toggle'=> 'enable']) ?>
    <?= Html::button('禁用', ['class' => 'btn btn-warning','data-toggle'=> 'disable']) ?>
    <?= Html::button('发布', ['class' => 'btn btn-info','data-toggle'=> 'isPub']) ?>
    <?= Html::button('取消发布', ['class' => 'btn btn-warning','data-toggle'=> 'noPub']) ?>
    <?= Html::button('推荐', ['class' => 'btn btn-info','data-toggle'=> 'isRec']) ?>
    <?= Html::button('取消推荐', ['class' => 'btn btn-warning','data-toggle'=> 'noRec']) ?>
    <?= Html::a('导出', [
        $attr_type ? 'export' : 'export_pha',
        'Hospital[title]' => $nameSearch,
        'Hospital[rid]' => $ridSearch,
    ], ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
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
    /*发布*/
    $('[data-toggle="isPub"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要发布选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#23c6c8",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','isPub');
            swal("已发布！", "", "success");
        });
    });
    /*取消发布*/
    $('[data-toggle="noPub"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要取消发布选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','noPub');
            swal("已取消发布！", "", "success");
        });
    });
    /*推荐*/
    $('[data-toggle="isRec"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要推荐选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#23c6c8",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','isRec');
            swal("已推荐！", "", "success");
        });
    });
    /*取消推荐*/
    $('[data-toggle="noRec"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要取消推荐选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','noRec');
            swal("已取消推荐！", "", "success");
        });
    });
JS;
$this->registerJs($js);
?>