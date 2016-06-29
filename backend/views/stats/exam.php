<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Exam;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '试卷统计';
$this->params['breadcrumbs'][] = $this->title;

$get = $yiiApp->request->get();
$startTimeSearch = $get['startTime'] ?? '';
$endTimeSearch = $get['endTime'] ?? '';
$nameSearch = $get['name'] ?? '';
backend\assets\AppAsset::register($this);
?>
    <div class="modal-body">
        <div class="box-body">
            <div class="hospital-search">
                <?php
                $form = ActiveForm::begin([
                    'action' => 'exam',
                    'method' => 'get',
                    'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
                ]); ?>
                <div class="form-group">
                    <label class="control-label">起始时间</label>
                    <input id="startTime" type="text" class="form-control layer-date" name="startTime" value="<?php echo $startTimeSearch?>">
                </div>
                <div class="form-group">
                    <label class="control-label">截止时间</label>
                    <input id="endTime" type="text" class="form-control layer-date" name="endTime" value="<?php echo $endTimeSearch?>">
                </div>
                <div class="form-group">
                    <label class="control-label">试卷名</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $nameSearch?>">
                </div>
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('导出', [
                    'exam-export',
                    'startTime' => $startTimeSearch,
                    'endTime' => $endTimeSearch,
                    'name' => $nameSearch,
                ], ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => '序号'
                        ],
                        [
                            'attribute' => 'name',
                            'value'=>
                                function($model){
                                    $result = Exam::findOne($model->exa_id);
                                    return  $result->name ?? '';
                                },
                        ],
                        [
                            'attribute' => 'num',
                            'label' => '答题人数',
                            'value'=>
                                function($model){
                                    $result = $model::find()->where(['exa_id' => $model->exa_id, 'status' => 1])->groupBy('uid')->count('id');
                                    return  $result;
                                },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{view}',
                            'header' => '操作',
                            'buttons' => [
                                'view'=> function ($url, $model, $key) {
                                    $aHtml = '<span class="glyphicon glyphicon-eye-open"></span>';
                                    return Html::a($aHtml,['exam-info','exa_id' => $model->exa_id]);
                                },
                            ],
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
$('#type-list-search').chosen({width: '240px'});
var start = {
    elem: '#startTime',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: '2000-00-00 00:00:00', //设定最小日期为当前日期
    max: '2099-06-16 23:59:59', //最大日期
    istime: true,
    istoday: false,
    choose: function(datas){
         end.min = datas; //开始日选好后，重置结束日的最小日期
         end.start = datas //将结束日的初始值设定为开始日
    }
};
var end = {
    elem: '#endTime',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: '2000-00-00 00:00:00',
    max: '2099-06-16 23:59:59',
    istime: true,
    istoday: false,
    choose: function(datas){
        start.max = datas; //结束日选好后，重置开始日的最大日期
    }
};
laydate(start);
laydate(end);
JS;
$this->registerJs($js);
?>