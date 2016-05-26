<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
$appYii = Yii::$app;
?>
<?php
$form = ActiveForm::begin([
    'action' => ['form'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
]); ?>
<div class="modal-body">
    <div class="form-group">
        <label class="control-label">考题目录</label>
        <select class="form-control" name="Exam[type]" data-toggle="exam-type">
            <?php
            foreach ($appYii->params['exam']['type'] as $key => $val){
                echo '<option value="' . $key . '">' . $val . '</option>';
            }
            ?>
        </select>
    </div>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'minutes')->textInput() ?>
    <div class="form-group">
        <label class="control-label">封面图</label>
        <?= $this->render('/webuploader/index',[
            'name' => 'Exam[imgurl]',
            'imgMaxSize' => 2097152,/*文件限制2M*/
        ]);?>
        <div class="help-block"></div>
    </div>
    <div class="form-group" data-toggle="custom-exam">
        <label class="control-label">试题</label>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>序号</th>
                    <th>类型</th>
                    <th>题目</th>
                    <th>选项</th>
                    <th>答案</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="examListBody">
            </tbody>
        </table>
        <button id="add-exercise" type="button" class="btn btn-info btn-sm">添加</button>
    </div>
    <div class="form-group" data-toggle="random-exam">
        <label class="control-label">考题目录</label>
        <select class="form-control" name="Exam[exercise-class]">
            <?php
                echo '<option selected="selected" value="">全部</option>';
            foreach ($examClassTree as $key => $val){
                echo '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group" data-toggle="random-exam">
        <label class="control-label">出题个数</label>
        <input name="Exam[exercise-count]" type="text" class="form-control">
    </div>
    <?= $form->field($model, 'about')->textarea() ?>
    <div class="form-group field-exercise-category required">
        <label class="control-label" for="exercise-category">评分规则</label>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th width="25%" >级别</th>
                <th width="15%" >条件</th>
                <th width="20%" >正确率</th>
                <th width="25%" >评分</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="examLevelListBody">
            </tbody>
        </table>
    </div>
    <?= $form->field($model, 'status')->dropDownList($appYii->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    
    /*默认自定义出题*/
    $('[data-toggle="random-exam"]').hide();
    $('[data-toggle="custom-exam"]').show();
    
    /*删除试题*/
    $('#examListBody').on('click','.delThisOption',function() {
       delThisRowOptionForMime('#examListBody', this, 0, 2);
    });
    /*添加试题*/
    $('#add-exercise').click(function() {
        layer.open({
            type: 2,
            title: '添加试题',
            area: ['800px', '600px'],
            fix: false, //不固定
            maxmin: true,
            content: '/exercise/index?Exercise[status]=1&hiboyiamalayer=itisevident'
        });
    });
    /*删除评分规则*/
    $('#examLevelListBody').on('click','.delThisOption',function() {
       delThisRowOptionForMime('#examLevelListBody',this, 1, 3);
    });
    /*添加评分规则*/
    $('#examLevelListBody').on('click','.addNextOption',function() {
       var thisTr = $(this).parent().parent();
        var datakey = parseInt(thisTr.attr('data-key'));
        var html = '';
        html += '<tr data-key="' + ( datakey + 1 ) + '">';
        html += '    <td>';
        html += '    <input type="hidden" name="ExamLevel[id][]" value="">';
        html += '    <input type="text" class="form-control" name="ExamLevel[level][]" value="">';
        html += '    </td>';
        html += '    <td><select class="form-control" name="ExamLevel[condition][]">';
        for(var j in conditionExamLevel){
            html += '<option value="' + j + '">' + conditionExamLevel[j] + '</option>';
        }
        html += '    </select></td>';
        html += '    <td><select class="form-control" name="ExamLevel[rate][]">';
        for(var j in rateExamLevel){
            html += '<option value="' + j + '">' + rateExamLevel[j] + '</option>';
        }
        html += '    </select></td>';
        html += '    <td><input type="text" class="form-control" name="ExamLevel[remark][]" value=""></td>';
        html += '    <td>';
        html += '       <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>';
        html += '       <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
        html += '    </td>';
        html += '</tr>';
        thisTr.after(html);
        $(this).remove();
    });
    
    $('[data-toggle="exam-type"]').change(function() {
        var checkValue = $(this).val();
        if(0 == checkValue){
            /*自定义出题*/
            $('[data-toggle="random-exam"]').hide();
            $('[data-toggle="custom-exam"]').show();
        }else if(1 == checkValue){
            /*随机出题*/
            $('[data-toggle="random-exam"]').show();
            $('[data-toggle="custom-exam"]').hide();
        }
    })

JS;
$this->registerJs($js);
?>
