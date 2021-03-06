<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Exercise;
use common\models\ExamLevel;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $examClass */
/* @var $dataProvider */
/* @var $params */
$appYii = Yii::$app;
$this->title = '考卷';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $appYii->params;

$conditionExamLevel = json_encode($appYii->params['examLevel']['condition']);
$rateExamLevel = json_encode($appYii->params['examLevel']['rate']);
backend\assets\AppAsset::register($this);
?>

<div class="modal-body">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['delete'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'delForm'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }
                    ],
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '序号'
                    ],
                    [
                        'attribute' => 'type',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['exam']['type'][$model->type];
                                return $result ?? '';
                            },
                    ],
                    'name',
                    'minutes',
                    [
                        'attribute' => 'publish_status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['pubStatusOption'][$model->publish_status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'publish_time',
                        'value' =>
                            function ($model) {
                                return $model->publish_status == 1 ? date('Y-m-d H:i:s', $model->publish_time) : '';
                            },
                    ],
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'recommend_status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['recStatusOption'][$model->recommend_status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update}',//只需要展示删除和更新
                        'buttons' => [
                            'update'=> function ($url, $model, $key) {
                                $exeIds = explode(',' , $model->exe_ids);
                                $exercise = Exercise::find()->andWhere(['id'=> $exeIds])->all();
                                $exerciseArray = [];
                                foreach ($exercise as $k => $val){
                                    $exerciseArray[$k]['type'] = $this->params['params']['exercise']['type'][$val->type];
                                    $exerciseArray[$k]['id'] = $val->id;
                                    $exerciseArray[$k]['question'] = $val->question;
                                    $exerciseArray[$k]['option'] = count(unserialize($val->option));
                                    $exerciseArray[$k]['answer'] = $val->answer;
                                }
                                $exerciseData = json_encode($exerciseArray);

                                $examLevel = ExamLevel::find()->andWhere(['exam_id'=> $model->id])->all();
                                $examLevelArray = [];
                                foreach ($examLevel as $k => $val){
                                    $examLevelArray[$k]['id'] = $val->id;
                                    $examLevelArray[$k]['condition'] = $val->condition;
                                    $examLevelArray[$k]['rate'] = $val->rate;
                                    $examLevelArray[$k]['level'] = $val->level;
                                    $examLevelArray[$k]['remark'] = $val->remark;
                                }
                                $examLevelData = json_encode($examLevelArray);
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#formModal" data-toggle="modal"
                                data-id="' . $model->id . '"
                                data-type="' . $model->type . '"
                                data-name="' . $model->name . '"
                                data-minutes="' . $model->minutes . '"
                                data-publish_status="' . $model->publish_status . '"
                                data-recommend_status="' . $model->recommend_status . '"
                                data-about="' . $model->about . '"
                                data-class_id="' . $model->class_id . '"
                                data-total="' . $model->total . '"
                                data-exercise=' . $exerciseData . '
                                data-examLevel=' . $examLevelData . '
                                data-status="' . $model->status . '"
                                 ></span>');
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<!-- 弹出层 -->
<div class="modal inmodal" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加</h4>
            </div>
            <?= $this->render('_form', [
                'model' => $searchModel,
                'examClassTree' => $examClassTree,
            ]); ?>
        </div>
    </div>
</div>

<?php
$formUrl = \yii\helpers\Url::toRoute('form');

$js=<<<JS
    var conditionExamLevel = $conditionExamLevel;
    var rateExamLevel = $rateExamLevel;
    
    /*修改试卷*/
    $("span[name='saveData']").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var name = $(this).attr('data-name');
        var minutes = $(this).attr('data-minutes');
        var publish_status = $(this).attr('data-publish_status');
        var recommend_status = $(this).attr('data-recommend_status');
        var about = $(this).attr('data-about');
        var exercise = JSON.parse($(this).attr('data-exercise'));
        var examLevelList = JSON.parse($(this).attr('data-examLevel'));
        var total = $(this).attr('data-total');
        var class_id = $(this).attr('data-class_id');
        var status = $(this).attr('data-status');
        /* 编辑初始化 */
        $('#formModal #tableForm').attr('action', '$formUrl?id='+id);
        $('#formModal #exam-type').val(type);
        $('#formModal #exam-name').val(name);
        $('#formModal #exam-minutes').val(minutes);
        $('#formModal #exam-publish_status').val(publish_status);
        $('#formModal #exam-recommend_status').val(recommend_status);
        $('#formModal #exam-about').val(about);
        $('#formModal #exam-class_id').val(class_id);
        $('#formModal #exam-total').val(total);
        $('#formModal #exam-status').val(status);
        examEditForMime($('#examListBody'), exercise);
        examLevelEditForMime($('#examLevelListBody'), examLevelList, conditionExamLevel, rateExamLevel);
        uploadResultInit();
        /*根据出题类别初始化出题*/
        if(0 == type){
            /*自定义出题*/
            $('[data-toggle="random-exam"]').hide();
            $('[data-toggle="custom-exam"]').show();
        }else if(1 == type){
            /*随机出题*/
            $('[data-toggle="random-exam"]').show();
            $('[data-toggle="custom-exam"]').hide();
        }
    });
    /*添加题库初始化*/
    $("#createBtn").click(function(){
        var defaltData = ''; 
        $('#formModal #tableForm').attr('action', '$formUrl');
        $('#formModal #exam-type').val(0);
        $('#formModal #exam-name').val(defaltData);
        $('#formModal #exam-minutes').val(defaltData);
        $('#formModal #exam-publish_status').val(defaltData);
        $('#formModal #exam-recommend_status').val(defaltData);
        $('#formModal #exam-about').val(defaltData);
        $('#formModal #exam-class_id').val(defaltData);
        $('#formModal #exam-total').val(defaltData);
        $('#formModal #exam-status').val(1);
        $('#examListBody').html(defaltData);
        examLevelInitForMime('#examLevelListBody', conditionExamLevel, rateExamLevel);
        uploadResultInit();
        /*初始化自定义出题*/
        $('[data-toggle="random-exam"]').hide();
        $('[data-toggle="custom-exam"]').show();
    });
	
	examLevelEditForMime = function (asThis, list, conditionExamLevel, rateExamLevel) {
        var html = '';
        var listLen = list.length;
        if(listLen > 0){
            for(var i = 0; i < listLen; i++){
                html += '<tr data-key="' + ( i + 1 ) + '">';
                html += '    <td>';
                html += '    <input type="hidden" name="ExamLevel[id][]" value="' + list[i]['id'] + '">';
                html += '    <input type="text" class="form-control" name="ExamLevel[level][]" value="' + list[i]['level'] + '">';
                html += '    </td>';
                html += '    <td><select class="form-control" name="ExamLevel[condition][]">';
                for(var j in conditionExamLevel){
                    html += '<option ';
                   if(j == list[i]['condition']){
                    html += 'selected="selected"';
                   }
                   html += ' value="' + j + '">' + conditionExamLevel[j] + '</option>';
                }
                html += '    </select></td>';
                html += '    <td><select class="form-control" name="ExamLevel[rate][]">';
                for(var j in rateExamLevel){
                    html += '<option ';
                  if(j == list[i]['rate']){
                    html += 'selected="selected"';
                  }
                   html += ' value="' + j + '">' + rateExamLevel[j] + '</option>';
                }
                html += '    </select></td>';
                html += '    <td><input type="text" class="form-control" name="ExamLevel[remark][]" value="' + list[i]['remark'] + '"></td>';
                html += '    <td>';
                html += '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>';
                if(i == listLen - 1){
                    html += '  <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
                }
                html += '    </td>';
                html += '</tr>';
            }
            asThis.html(html);
        }else {
            examLevelInitForMime('#examLevelListBody', conditionExamLevel, rateExamLevel);
        }
    };
    
    examLevelInitForMime = function (element, conditionExamLevel, rateExamLevel) {
        var html = '';
        html += '<tr data-key="1">';
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
        html += '    </td>';
        html += '</tr>';
        html += '<tr data-key="2">';
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
        html += '    </td>';
        html += '</tr>';
        html += '<tr data-key="3">';
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
        html += '    </td>';
        html += '</tr>';
        html += '<tr data-key="4">';
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
        $(element).html(html);
    }
    
JS;
$this->registerJs($js);
?>



