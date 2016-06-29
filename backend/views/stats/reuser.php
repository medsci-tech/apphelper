<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Member;
use common\models\ResourceStudy;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '资源统计';
$this->params['breadcrumbs'][] = $this->title;

$get = $yiiApp->request->get();
$startTimeSearch = $get['startTime'] ?? '';
$endTimeSearch = $get['endTime'] ?? '';
$usernameSearch = $get['username'] ?? '';
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body">
        <div class="hospital-search">
            <?php
            $form = ActiveForm::begin([
                'action' => 'reuser',
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
                <label class="control-label">手机号</label>
                <input type="text" class="form-control" name="username" value="<?php echo $usernameSearch?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('导出', [
                'reuser-export',
                'startTime' => $startTimeSearch,
                'endTime' => $endTimeSearch,
                'username' => $usernameSearch,
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
                        'attribute' => 'real_name',
                        'value'=>
                            function($model){
                                $result = Member::findOne($model->uid);
                                $this->params['stats']['username'] = $result->username ?? '';
                                $this->params['stats']['nickname'] = $result->nickname ?? '';
                                return  $result->real_name ?? '';
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['username'];
                            },
                    ],
                    [
                        'attribute' => 'view',
                        'value'=>
                            function($model){
                                $result = $model::find()->where(['uid' => $model->uid])->count('id');
                                return  $result;
                            },
                    ],
                    [
                        'attribute' => 'times',
                        'value'=>
                            function($model){
                                $result = $model::find()->where(['uid' => $model->uid])->sum('times');
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
                                return Html::a($aHtml,['reuser-yi','uid' => $model->uid]);
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