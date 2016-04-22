<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use common\models\Hospital;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProvider  */
/* @var $params */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $params;
backend\assets\AppAsset::register($this);
?>
<div class="article-index">
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">用户搜索</h2></div>
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel,'uploadModel'=>$uploadModel]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }

                    ],
                    'real_name',
                    'nickname',
                    'username',
                    'email',
                    [
                        'attribute' => 'hospital_id',
                        'value'=>
                            function($model){
                                $result = Hospital::findOne($model->hospital_id);
                                return  $result ? $result->name : '';
                            },
                    ],
                    [
                        'attribute' => 'rank_id',
                        'value'=>
                            function($model){
                                $result = $this->params['params']['member']['rank'][$model->rank_id];
                                return  $result ? $result : '';
                            },
                    ],
                    [
                        'attribute' => 'province_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->province_id);
                                return  $result ? $result->name : '';
                            },
                    ],
                    [
                        'attribute' => 'city_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->city_id);
                                return  $result ? $result->name : '';
                            },
                    ],
                    [
                        'attribute' => 'area_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->area_id);
                                return  $result ? $result->name : '';
                            },
                    ],
                    'created_at:date',
                    [
                        'attribute' => 'status',
                        'value'=>
                            function($model){
                                $result = $this->params['params']['statusOption'][$model->status];
                                return  $result ? $result : '';
                            },
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
