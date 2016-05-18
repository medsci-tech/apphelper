<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Exercise;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $examClass */
/* @var $dataProvider */
/* @var $params */
$yiiApp = Yii::$app;
$this->title = '考卷';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
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
                    ['class' => 'yii\grid\SerialColumn'],
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
                    'publish_at',
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
                                $exeIds = explode(',' , mb_substr($model->exe_ids, 1, -1));
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
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#formModal" data-toggle="modal"
                                data-id="' . $model->id . '"
                                data-type="' . $model->type . '"
                                data-name="' . $model->name . '"
                                data-minutes="' . $model->minutes . '"
                                data-publish_status="' . $model->publish_status . '"
                                data-recommend_status="' . $model->recommend_status . '"
                                data-about="' . $model->about . '"
                                data-exercise=' . $exerciseData . '
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
    if('$getError'){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.error('$getError');
    }else if('$getSuccess'){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.success('$getSuccess');
    }
    
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
        var status = $(this).attr('data-status');
        /* 编辑初始化 */
        $('#formModal #tableForm').attr('action', '$formUrl?id='+id);
        $('#formModal #exam-type').val(type);
        $('#formModal #exam-name').val(name);
        $('#formModal #exam-minutes').val(minutes);
        $('#formModal #exam-publish_status').val(publish_status);
        $('#formModal #exam-recommend_status').val(recommend_status);
        $('#formModal #exam-about').val(about);
        $('#formModal #exam-status').val(status);
        examEditForMime($('#examListBody'), exercise);
        
    });
    /*添加题库初始化*/
    $("#createBtn").click(function(){
        /* 编辑初始化 */
        var defaltData = ''; 
        $('#formModal #tableForm').attr('action', '$formUrl');
        $('#formModal #exam-type').val(0);
        $('#formModal #exam-name').val(defaltData);
        $('#formModal #exam-minutes').val(defaltData);
        $('#formModal #exam-publish_status').val(defaltData);
        $('#formModal #exam-recommend_status').val(defaltData);
        $('#formModal #exam-about').val(defaltData);
        $('#formModal #exam-status').val(1);
        $('#examListBody').html(defaltData);
        console.log('$formUrl');
    });
JS;
$this->registerJs($js);
?>



