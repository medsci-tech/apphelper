<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">用户搜索</h2></div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
        <div class="box-header">
            <h2 class="box-title">用户列表</h2>
            <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'real_name',
                    'username',
                    'email',
//                    [
//                        'attribute' => 'province_id',
//                        'value'=>
//                            function($model){
//                                $result = Region::findOne($model->province_id);
//                                return  $result->name;
//                            },
//                    ],
                    'city_id',
                    'area_id',
                    'hospital_id',
                    'rank_id',
                    'created_at',
//                    'status:boolean',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
