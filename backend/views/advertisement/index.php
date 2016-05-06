<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 16:49
 */
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use yii\widgets\ActiveForm;

$this->title = '广告轮播';
$this->params['breadcrumbs'][] = $this->title;
?>

<p></p>
<div class="ad-index">
    <div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['modify'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'modifyForm'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
<!--            --><?//= GridView::widget([
//                'dataProvider' => $dataProvider,
//                'columns' => [
//                    [
//                        'class' => 'yii\grid\CheckboxColumn',
//                        'checkboxOptions' => function($model, $key, $index, $column) {
//                            return ['value' => $model->id];
//                        }
//
//                    ],
//                    'id',
//                    'name',
//                    [
//                        'attribute' => 'province_id',
//                        'value'=>
//                            function($model){
//                                $result = Region::findOne($model->province_id);
//                                return  $result->name;
//                            },
//                    ],
//                    [
//                        'attribute' => 'city_id',
//                        'value'=>
//                            function($model){
//                                $result = Region::findOne($model->city_id);
//                                return  $result->name;
//                            },
//                    ],
//                    [
//                        'attribute' => 'area_id',
//                        'value'=>
//                            function($model){
//                                $result = Region::findOne($model->area_id);
//                                return  $result->name;
//                            },
//                    ],
//                    'address',
//                    [
//                        'attribute' => 'status',
//                        'value'=>
//                            function($model){
//                                if($model->status == 1) {
//                                    return  '启用';
//                                } else {
//                                    return  '禁用';
//                                }
//                            },
//                    ],
//                    // 'created_at',
//                    // 'updated_at',
//                    // 'status',
//                    // 'cover',
//
//                    [
//                        'class' => 'yii\grid\ActionColumn',
//                        'template'=>'{view}  {update} {delete}',
//                        'header' => '操作',
//                        'buttons'=>[
//                            'update'=> function ($url, $model, $key) {
//                                return Html::a('<span name="del" class="glyphicon glyphicon-pencil" data-target="#myModal" data-toggle="modal"
//                                names="'.$model->name.'"
//                                address="'.$model->address.'"
//                                id="'.$model->id.'"
//                                province_id="'.$model->province_id.'"
//                                city_id="'.$model->city_id.'"
//                                area_id="'.$model->area_id.'"
//                                 ></span>');
//                            },
//                        ]
//                    ]
//                ],
//            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><label id="l_title">单位发布</label></h4>
            </div>
            <?=$this->render('create', [
                'model' => $model,
            ]);?>
        </div>
    </div>
</div>
</div>