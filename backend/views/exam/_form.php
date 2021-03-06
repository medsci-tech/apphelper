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
    <?= $form->field($model, 'type')->dropDownList($appYii->params['exam']['type'],['data-toggle' => 'exam-type']) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'minutes')->textInput() ?>
    <div class="form-group">
        <label class="control-label">封面图</label>
        <?= $this->render('/webuploader/index',[
            'name' => 'Exam[imgurl]',
            'uploadPath' => 'image/exam',
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
        <select class="form-control" name="Exam[class_id]" id="exam-class_id">
            <?php
                echo '<option selected="selected" value="">全部</option>';
            if($examClassTree){
                foreach ($examClassTree as $key => $val){
                    echo '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group" data-toggle="random-exam">
        <label class="control-label">出题个数</label>
        <input name="Exam[total]" id="exam-total" type="text" class="form-control">
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
    <?= Html::button('确定', ['class' => 'btn btn-primary', 'id' => 'btnFormSubmit']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    
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
    
$('#formModal').on('click','#btnFormSubmit',function() {
    var data = {};var dataLevel = {};  
    var elmeParent = '#formModal';  
    var action = $(elmeParent + ' #tableForm').attr('action');
    var href = window.location.href;
    data.type = $(elmeParent + ' #exam-type').val();
    data.name = $(elmeParent + ' #exam-name').val();
    data.minutes = $(elmeParent + ' #exam-minutes').val();
    data.imgurl = $(elmeParent + ' [data-toggle="upload-saveInput"]').val();
    data.about = $(elmeParent + ' #exam-about').val();
    data.total = $(elmeParent + ' #exam-total').val();
    data.class_id = $(elmeParent + ' #exam-class_id').val();
    data.status = $(elmeParent + ' #exam-status').val();
    

    var examList = $('#examListBody');
    var examLevelList = $('#examLevelListBody');
    /*试题*/
    var dataList = examList.find('[name="Exam[exe_ids][]"]');
    data.exe_ids = getDataListForMime(dataList);
    
    /*等级id*/
    dataList = examLevelList.find('[name="ExamLevel[id][]"]');
    dataLevel.id = getDataListForMime(dataList);
    /*等级*/
    dataList = examLevelList.find('[name="ExamLevel[level][]"]');
    dataLevel.level = getDataListForMime(dataList);
    /*等级条件*/
    dataList = examLevelList.find('[name="ExamLevel[condition][]"]');
    dataLevel.condition = getDataListForMime(dataList);
    /*等级正确率*/
    dataList = examLevelList.find('[name="ExamLevel[rate][]"]');
    dataLevel.rate = getDataListForMime(dataList);
    /*等级评分*/
    dataList = examLevelList.find('[name="ExamLevel[remark][]"]');
    dataLevel.remark = getDataListForMime(dataList);     
    console.log(data);
    console.log(dataLevel);
    subActionAjaxForMime('post', action, {'Exam':data, 'ExamLevel':dataLevel}, href);
});



JS;
$this->registerJs($js);
?>
