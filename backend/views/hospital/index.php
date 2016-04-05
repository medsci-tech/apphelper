<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '单位';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                    'province_id',
                    'city_id',
                    'area_id',
                    'address',
                    // 'author',
                    // 'created_at',
                    // 'updated_at',
                    // 'status',
                    // 'cover',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>