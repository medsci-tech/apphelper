<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Resource;
use common\models\ResourceStudy;
use common\models\ResourceClass;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '资源统计';
$this->params['breadcrumbs'][] = $this->title;

$get = $yiiApp->request->get();
$nameSearch = $get['title'] ?? '';
$this->params['stats']['attrType'] = $yiiApp->params['resourceClass']['attrType'];
$this->params['stats']['memberInfo'] = $memberInfo;
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body">
        <div class="hospital-search">
            <?php
            $form = ActiveForm::begin([
                'action' => 'resource-yi',
                'method' => 'get',
                'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
            ]); ?>
            <?= Html::a('返回', $referrerUrl ?? 'index', ['class' => 'btn btn-white']) ?>
            <div class="form-group">
                <label class="control-label">资源名</label>
                <input type="text" class="form-control" name="title" value="<?php echo $nameSearch?>">
            </div>

            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('导出', 'explode', ['class' => 'btn btn-success']) ?>
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
                                return  $this->params['stats']['memberInfo']['real_name'];
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['memberInfo']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['memberInfo']['username'];
                            },
                    ],
                    [
                        'attribute' => 'title',
                        'value'=>
                            function($model){
                                $result = Resource::findOne($model->rid);
                                $this->params['stats']['attr_type'] = ResourceClass::findOne($result->rid)->attr_type;
                                return  $result->title ?? '';
                            },
                    ],
                    [
                        'attribute' => 'attr_type',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['attrType'][$this->params['stats']['attr_type']];
                            },
                    ],

                    [
                        'attribute' => 'view',
                        'value'=>
                            function($model){
                                $result = ResourceStudy::find()->where(['uid' => $model->uid, 'rid' => $model->rid])->count('id');
                                return  $result;
                            },
                    ],
                    [
                        'attribute' => 'times',
                        'value'=>
                            function($model){
                                $result = ResourceStudy::find()->where(['uid' => $model->uid, 'rid' => $model->rid])->sum('times');
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
                                return Html::a($aHtml,['reuser-er','rid'=>$model->rid, 'uid' => $model->uid]);
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