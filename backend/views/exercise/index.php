<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use common\models\Hospital;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $examClass */
/* @var $dataProvider */
/* @var $params */
$yiiApp = Yii::$app;
$this->title = '题库';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $params;
$this->params['hiboyiamalayer'] = $yiiApp->request->get()['hiboyiamalayer'] ?? '';
backend\assets\AppAsset::register($this);
/*根据get参数判断是否是考卷添加试题*/
?>
<!--树形视图--start-->
<div id="treeView" class="col-lg-2 modal-body"></div>
<!--树形视图--end-->

<div class="modal-body col-lg-10">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel, 'examAddExerciseForGet' => $this->params['hiboyiamalayer']]); ?>
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
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'type',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['exercise']['type'][$model->type];
                                return $result ?? '';
                            },
                    ],
                    'question',
                    [
                        'attribute' => 'option',
                        'value' => function ($model) {
                            return count(unserialize($model->option));
                        }
                    ],
                    'answer',
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update}',//只需要展示删除和更新
                        'buttons' => [
                            'update'=> function ($url, $model, $key) {
                                $aHtml = '';
                                if(empty($this->params['hiboyiamalayer'])){
                                    $aHtml = '<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#formModal" data-toggle="modal"
                                data-id="'.$model->id.'"
                                data-type="'.$model->type.'"
                                data-category="'.$model->category.'"
                                data-question="'.$model->question.'"
                                data-option='.json_encode(unserialize($model->option)).'
                                data-answer="'.$model->answer.'"
                                data-keyword="'.$model->keyword.'"
                                data-resolve="'.$model->resolve.'"
                                data-status="'.$model->status.'"
                                 ></span>';
                                }
                                return Html::a($aHtml);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php if($this->params['hiboyiamalayer']):?>
        <?= Html::button('移入', ['class' => 'btn-outline btn btn-success','data-toggle'=> 'layerCtrlParent']) ?>
    <?php endif;?>
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
            ]); ?>
        </div>
    </div>
</div>

<?php
$formUrl = \yii\helpers\Url::toRoute('form');
$getError = $yiiApp->getSession()->getFlash('error');
$getSuccess = $yiiApp->getSession()->getFlash('success');
$js=<<<JS
    /*修改操作状态提示*/
    if('$getError' || '$getSuccess'){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
        }
    }
    if('$getError'){
        toastr.error('$getError');
    }else if('$getSuccess'){
        toastr.success('$getSuccess');
    }
    
    /*树形结构初始化*/
	var initSelectableTree = function() {
		return $('#treeView').treeview({
		    levels: 1,
		    onSubmitFormId: 'w0',
		    onSubmitInputValue: 'exercise-category',
			data: $examClass
		});
	};
	var selectableTree = initSelectableTree();
	var findSelectableNodes = function() {
		return selectableTree.treeview('search', [ '$treeNavigateSelectedName', { ignoreCase: false, exactMatch: false } ]);
	};
	var selectableNodes = findSelectableNodes();
	
    /*修改题库*/
    $("span[name='saveData']").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var category = $(this).attr('data-category');
        var question = $(this).attr('data-question');
        var option = JSON.parse($(this).attr('data-option'));
        var answer = $(this).attr('data-answer');
        var keyword = $(this).attr('data-keyword');
        var resolve = $(this).attr('data-resolve');
        var status = $(this).attr('data-status');
        /* 编辑初始化 */
        $('#formModal #tableForm').attr('action','$formUrl?id='+id);
        $('#formModal #exercise-type').val(type);
        $('#formModal #exercise-category').val(category);
        $('#formModal #exercise-question').val(question);
        $('#formModal #exercise-keyword').val(keyword);
        $('#formModal #exercise-resolve').val(resolve);
        $('#formModal #exercise-status').val(status);
        var checkType = 'radio';
        if(type == 2){
            checkType = 'checkbox';
        }
        exerciseEditForMime('#optionListBody', option, answer, checkType);
    });
    /*添加题库初始化*/
    $("#createBtn").click(function(){
        var defaltData = ''; 
        $('#formModal #tableForm').attr('action','$formUrl');
        $('#formModal #exercise-type').val(1);
        $('#formModal #exercise-category').val(defaltData);
        $('#formModal #exercise-question').val(defaltData);
        $('#formModal #exercise-keyword').val(defaltData);
        $('#formModal #exercise-resolve').val(defaltData);
        $('#formModal #exercise-status').val(1);
        exerciseInitForMime('#optionListBody');
    });
    
    $('[data-toggle="layerCtrlParent"]').on('click',function() {
        var check = $('#delForm').find('input[name="selection[]"]');
        var parentHtml = parent.$('#formModal #examListBody');
        var parentLastNum = $(parentHtml.html()).length;
        if('' == parentHtml || undefined == parentHtml){
            parentLastNum = 0;
        }
        var html = '';
        for(var i =0; i < check.length; i++){
            if(check[i].checked == true){
                parentLastNum++;
                var tdHtml = $(check[i]).parent().next().next();
                html += '<tr data-key="' + parentLastNum + '">';
                html += '    <td>' + parentLastNum + '</td>';
                html += '    <td>' + tdHtml.html() + '<input type="hidden" name="Exam[exe_ids][]" value="' + $(check[i]).val() + '"></td>';
                html += '    <td><a href="/exercise/view?id=' + $(check[i]).val() + '">' + tdHtml.next().html() + '</a></td>';
                html += '    <td>' + tdHtml.next().next().html() + '</td>';
                html += '    <td>' + tdHtml.next().next().next().html() + '</td>';
                html += '    <td>';
                html += '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>';
                html += '    </td>';
                html += '</tr>';
            }
        }
        if('' == html){
            swal('未选择','请勾选需要操作的信息');
            return false;
        }
        parentHtml.append(html);
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
        parent.layer.close(index);
    });
    
/**
 * 试题管理-题库添加试题-初始化选项-默认四个选项
 * @param element string | eg:'#div'
 */
exerciseInitForMime = function (element) {
    var html = ''
        + '<tr data-key="1">'
        + '    <td>A</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="A"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="2">'
        + '    <td>B</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="B"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="3">'
        + '    <td>C</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="C"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="4">'
        + '    <td>D</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="D"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '        <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>'
        + '    </td>'
        + '</tr>';
    $(element).html(html);
};
JS;
$this->registerJs($js);
?>



