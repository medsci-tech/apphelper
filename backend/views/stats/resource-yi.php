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
$usernameSearch = $get['username'] ?? '';
$rid = $get['rid'];

$this->params['stats']['resourceInfo'] = $resourceInfo;

backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body">
        <div class="hospital-search">
            <?php
            $form = ActiveForm::begin([
                'action' => ['resource-yi', 'rid' => $rid],
                'method' => 'get',
                'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
            ]); ?>
            <?= Html::a('返回', $referrerUrl, ['class' => 'btn btn-white']) ?>
            <div class="form-group">
                <label class="control-label">手机号</label>
                <input type="text" class="form-control" name="username" value="<?php echo $usernameSearch?>">
            </div>

            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('导出', [
                'resource-yi-export',
                'rid' => $rid,
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
                                return Html::a($aHtml,['resource-er','rid'=>$model->rid, 'uid' => $model->uid]);
                            },
                        ],
                    ]
                ],
            ]); ?>
        </div>
    </div>
</div>
