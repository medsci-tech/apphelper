<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
?>

<div class="modal-body">

    <?php
    $form = ActiveForm::begin([
    'action' => ['form'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
    ]); ?>
    <?= $form->field($model, 'type')->dropDownList(Yii::$app->params['exam']['type']) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'minutes')->textInput() ?>
    <div class="form-group">
        <label class="control-label">试题</label>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>类型</th>
                    <th>题目</th>
                    <th>选项</th>
                    <th>答案</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="optionListBody">
            </tbody>
        </table>
    </div>

    <?= $form->field($model, 'about')->textarea() ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

    <div class="form-group">
        <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    /*删除试题*/
    $('#optionListBody').on('click','.delThisOption',function() {
       delThisRowOptionForMime('#optionListBody',this);
    });
    /*添加试题*/
    $('#optionListBody').on('click','.addNextOption',function() {

    });
JS;
$this->registerJs($js);
?>
