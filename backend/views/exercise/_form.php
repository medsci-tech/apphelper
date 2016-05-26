<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $examClassTree  */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
?>
<?php
$form = ActiveForm::begin([
    'action' => ['form'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'type')->dropDownList(Yii::$app->params['exercise']['type']) ?>
    <?= $form->field($model, 'category')->dropDownList($examClassTree) ?>
    <?= $form->field($model, 'question')->textInput() ?>
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
    
    <?= $form->field($model, 'keyword')->textInput(['placeholder' => '关键词可添加多个，用“|”分开']) ?>
    <?= $form->field($model, 'resolve')->textarea() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
$(function() {
    /*删除题库选项*/
    $('#optionListBody').on('click','.delThisOption',function() {
        delThisRowOptionForMime('#optionListBody', this, 1);
    });
    /*添加题库选项*/
    $('#optionListBody').on('click','.addNextOption',function() {
        var thisTr = $(this).parent().parent();
        var datakey = parseInt(thisTr.attr('data-key'));
        var thisLatter = String.fromCharCode(65 + datakey);
        var checkValue = $('#exercise-type').val();
        var checkType = 'radio';
        if(checkValue == 2){
            checkType = 'checkbox';
        }
        var trHtml = ''
            + '<tr data-key="' + ( datakey + 1 ) + '">'
            + '    <td>' +thisLatter+ '</td>'
            + '    <td><input type="text" class="form-control" name="Exercise[option][]"></td>'
            + '    <td><input type="' + checkType + '" class="checkValue" name="Exercise[answer][]" value="' +thisLatter+ '"></td>'
            + '    <td>'
            + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
            + '        <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>'
            + '    </td>'
            + '</tr>';
        thisTr.after(trHtml);
        $(this).remove();
    });
    /*题目单选多选切换*/
    $('#exercise-type').change(function() {
        var checkValue = $('#optionListBody').find('.checkValue');
        if(1 == $(this).val()){
            checkValue.attr('type','radio');
        }else {
            checkValue.attr('type','checkbox');
        }
    });
    
});

JS;
$this->registerJs($js);
?>
