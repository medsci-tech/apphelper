<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="hospital-search">

    <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
    ]); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'url') ?>

    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('添加', 'create', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
    /*启用*/
    $("#btn_enable").click(function(){
        /*判断是否有选中*/
        var check = $('#modifyForm').find('input[name="selection[]"]');
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
    /*禁用*/
    $("#btn_disable").click(function(){
        /*判断是否有选中*/
        var check = $('#modifyForm').find('input[name="selection[]"]');
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

JS;
$this->registerJs($js);
?>