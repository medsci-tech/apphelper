<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '单位';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('添加单位', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="hospital-index">
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">单位搜索</h2></div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">单位列表</h2></div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'province_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->province_id);
                                return  $result->name;
                            },
                    ],
                    [
                        'attribute' => 'city_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->city_id);
                                return  $result->name;
                            },
                    ],
                    [
                        'attribute' => 'area_id',
                        'value'=>
                            function($model){
                                $result = Region::findOne($model->area_id);
                                return  $result->name;
                            },
                    ],
                    'address',
                    // 'created_at',
                    // 'updated_at',
                    // 'status',
                    // 'cover',

                    ['class' => 'yii\grid\ActionColumn', 'header' => '操作'],
                ],
            ]); ?>
        </div>
    </div>
</div>