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
$examAddExerciseSatus = $examAddExerciseForGet ? 1 : '';
?>

<div class="hospital-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index', 'hiboyiamalayer' => $examAddExerciseForGet,'Exercise[status]' => $examAddExerciseSatus],
        'method' => 'get',
        'options' => ['class' => 'form-inline navbar-btn'],
    ]); ?>

    <?= $form->field($model, 'question') ?>
    <?= $form->field($model, 'category')->hiddenInput()->label(false) ?>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?php if(empty($examAddExerciseForGet)):?>
    <?= Html::button('添加', ['class' => 'btn btn-success animation_select','id'=>'createBtn','data-toggle'=>'modal','data-target'=>'#formModal']) ?>
    <?= Html::button('启用', ['class' => 'btn btn-info','data-toggle'=> 'enable']) ?>
    <?= Html::button('禁用', ['class' => 'btn btn-warning','data-toggle'=> 'disable']) ?>
    <?php endif;?>
    <?php ActiveForm::end(); ?>
</div>
<?php
$js = <<<JS
    /*删除*/
    $('#del').click(function() {
        /*判断是否有选中*/
        var check = $('#delForm').find('input[name="selection[]"]');
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
            confirmButtonText: "删除",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','del');
            swal("删除成功！", "您已经永久删除了信息。", "success");
        });
    });
    /*禁用*/
    $('[data-toggle="disable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#delForm').find('input[name="selection[]"]');
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
            confirmButtonText: "禁用",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','disable');
            swal("禁用成功！", "", "success");
        });
    });
    /*启用*/
    $('[data-toggle="enable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#delForm').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要启用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#1ab394",
            confirmButtonText: "启用",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','enable');
            swal("启用成功！", "", "success");
        });
    });
JS;
$this->registerJs($js);
?>