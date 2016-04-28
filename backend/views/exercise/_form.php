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
    <?= $form->field($model, 'type')->dropDownList(Yii::$app->params['exercise']['type']) ?>
    <?= $form->field($model, 'category')->textInput() ?>
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
                <td><input type="text" class="form-control" name="Exercise[option][]"></td>
                <td><input type="checkbox" class="checkValue" name="Exercise[answer][]" value="A"></td>
                <td><a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a></td>
            </tr>
            <tr data-key="2">
                <td>B</td>
                <td><input type="text" class="form-control" name="Exercise[option][]"></td>
                <td><input type="checkbox" class="checkValue" name="Exercise[answer][]" value="B"></td>
                <td><a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a></td>
            </tr>
            <tr data-key="3">
                <td>C</td>
                <td><input type="text" class="form-control" name="Exercise[option][]"></td>
                <td><input type="checkbox" class="checkValue" name="Exercise[answer][]" value="C"></td>
                <td>
                    <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>
                    <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    
    <?= $form->field($model, 'keyword')->textInput() ?>
    <?= $form->field($model, 'resolve')->textarea() ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

    <div class="form-group">
        <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(function() {
    $('#optionListBody').on('click','.delThisOption',function() {
        var optionListCount = $('#optionListBody').find('tr').length;
        if(optionListCount > 1){
            var parentTr = $(this).parent().parent();
            var nextTr = parentTr.nextAll('tr');
            var addHtmlLength = $(this).next('a').length;
            if(addHtmlLength){
                var addHtml = ' <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
                parentTr.prev('tr').find('td').last().append(addHtml);
            }
            for (var i = 0; i < parentTr.nextAll('tr').length; i++){
                var thisTr = $(parentTr.nextAll('tr')[i]);
                var thisTd = thisTr.find('td');
                var datakey = parseInt(thisTr.attr('data-key'));
                var thisLatter = String.fromCharCode(63 + datakey);
                thisTr.attr('data-key',datakey - 1);
                thisTd.eq(0).text(thisLatter);
                thisTd.find('.checkValue').val(thisLatter);
                console.log(thisLatter);
            }
            parentTr.remove();
        }
    });
    $('#optionListBody').on('click','.addNextOption',function() {
        var thisTr = $(this).parent().parent();
        var datakey = parseInt(thisTr.attr('data-key'));
        var thisLatter = String.fromCharCode(65 + datakey);
        console.log(thisTr);
        // return false;
        var trHtml = ''
            + '<tr data-key="' + ( datakey + 1 ) + '">'
            + '    <td>' +thisLatter+ '</td>'
            + '    <td><input type="text" class="form-control" name="Exercise[option][]"></td>'
            + '    <td><input type="checkbox" class="checkValue" name="Exercise[answer][]" value="' +thisLatter+ '"></td>'
            + '    <td>'
            + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
            + '        <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>'
            + '    </td>'
            + '</tr>';
        thisTr.after(trHtml);
        $(this).remove();
    });
    
});

JS;
$this->registerJs($js);
?>
