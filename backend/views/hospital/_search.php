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
?>

<div class="hospital-search">

    <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'name') ?>
    <div class="form-group">
        <label class="control-label"></label>
        <?= $this->render('/region/index',[
            'model' => $model,
            'm' => 'Hospital',
            'form' => $form,
        ]);?>
    </div>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::button('添加单位', ['id'=>'btn_add','class' => 'btn btn-success','data-toggle'=>'modal','data-target'=>"#myModal"]) ?>
    <?= Html::button('启用', ['id'=>'btn_enable','class' => 'btn btn-primary']) ?>
    <?= Html::button('禁用', ['id'=>'btn_disable','class' => 'btn btn-warning']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    /*启用*/
    $("#btn_enable").click(function(){
        swal({
            title: "您确定要启用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#1ab394",
            confirmButtonText: "启用",
            closeOnConfirm: false
        }, function () {
            subAction('typeForm','enable');
            swal("启用成功！", "", "success");
        });
    });
    /*禁用*/
    $("#btn_disable").click(function(){
         swal({
            title: "您确定要禁用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "禁用",
            closeOnConfirm: false
        }, function () {
            subAction('typeForm','disable');
            swal("禁用成功！", "", "success");
        });
    });

    function subAction(formId,val) {
        $("#" + formId).val(val);
        $('#'+formId).submit();
    }
JS;
$this->registerJs($js);
?>