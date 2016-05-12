<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
    <div class="form-group field-exercise-category required">
        <label class="control-label" for="exercise-category">类别</label>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>选项</th>
                <th>答案</th>
                <th>是否正确</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="optionListBody">
            <tr data-key="1">
                <td>A</td>
                <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>
                <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="A"></td>
                <td>
                    <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>
                </td>
            </tr>
            <tr data-key="2">
                <td>B</td>
                <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>
                <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="B"></td>
                <td>
                    <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>
                </td>
            </tr>
            <tr data-key="3">
                <td>C</td>
                <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>
                <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="C"></td>
                <td>
                    <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>
                </td>
            </tr>
            <tr data-key="4">
                <td>D</td>
                <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>
                <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="D"></td>
                <td>
                    <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>
                    <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>
                </td>
            </tr>
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
    /*删除题库选项*/
    $('#optionListBody').on('click','.delThisOption',function() {
       
    });
    /*添加题库选项*/
    $('#optionListBody').on('click','.addNextOption',function() {

    });
    /*题目单选多选切换*/
    $('#exercise-type').change(function() {

    });
    
JS;
$this->registerJs($js);
?>
