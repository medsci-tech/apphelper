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
        <?= Html::a('返回', $referrerUrl, ['class' => 'btn btn-white']) ?>
        <?= Html::a('导出', [
            'resource-er-export',
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
                    'times',
                ],
            ]); ?>
        </div>
    </div>
</div>
