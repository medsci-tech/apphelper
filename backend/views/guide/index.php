<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/12
 * Time: 11:50
 */
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('添加广告', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="hospital-index">
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">广告搜索</h2></div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
        <div class="box-header"><h2 class="box-title">广告列表</h2></div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'title',
                    'url',
                    [
                        'attribute' => 'created_at',
                        'value'=>
                            function($model){
                                $result = date('Y-m-d H:i:s',$model->created_at);
                                return  $result;
                            },
                    ],
                    // 'updated_at',
                    // 'status',
                    // 'cover',

                    ['class' => 'yii\grid\ActionColumn', 'header' => '操作'],
                ],
            ]); ?>
        </div>
    </div>
</div>