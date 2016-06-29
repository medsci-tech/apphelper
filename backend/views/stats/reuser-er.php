<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Resource;
use common\models\Member;
use common\models\ResourceStudy;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资源统计';
$this->params['breadcrumbs'][] = $this->title;

$this->params['stats']['memberInfo'] = $memberInfo;
$this->params['stats']['resourceInfo'] = $resourceInfo;
$get = Yii::$app->request->get();
$uid = $get['uid'];
$rid = $get['rid'];
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="navbar-btn">
        <?= Html::a('返回', $referrerUrl ?? 'reuser', ['class' => 'btn btn-white']) ?>
        <?= Html::a('导出', [
            'reuser-er-export',
            'rid' => $rid,
            'uid' => $uid,
        ], ['class' => 'btn btn-success']) ?>
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
                    'created_at:datetime',

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
                                return  $this->params['stats']['resourceInfo']['title'];
                            },
                    ],
                    [
                        'attribute' => 'attr_type',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['resourceInfo']['attr_type'];
                            },
                    ],
                    'times',
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