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
    <button type="button" name="doadd" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        添加单位
    </button>
</p>
<div class="hospital-index">
    <div>
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

<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">单位发布</h4>
            </div>
<?=$this->render('create', [
    'model' => $model,
]);?>
        </div>
    </div>
</div>
</div>
<?php
$js=<<<JS
 $(document).ready(function(){
    $('div').removeClass('container-fluid'); // 去除多余样式



});
JS;
$this->registerJs($js);
?>