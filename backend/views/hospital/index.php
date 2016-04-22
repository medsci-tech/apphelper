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
<p></p>
<div class="hospital-index">
    <div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
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

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}  {update} {delete}',
                        'header' => '操作',
                        'buttons'=>[
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="del" class="glyphicon glyphicon-pencil" data-target="#myModal" data-toggle="modal"
                                names="'.$model->name.'"
                                address="'.$model->address.'"
                                id="'.$model->id.'"
                                province_id="'.$model->province_id.'"
                                city_id="'.$model->city_id.'"
                                area_id="'.$model->area_id.'"
                                 ></span>');
                            },
                        ]
                    ]
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

    $("span[name='del']").click(function(){
    var id = $(this).attr('id');
    var name = $(this).attr('names');
    var address = $(this).attr('address');
    var province_id = $(this).attr('province_id');
    var city_id = $(this).attr('city_id');
    var area_id = $(this).attr('area_id');
    /* 编辑初始化 */
    $('#id').val(id);
    $('#name').val(name);
    $('#address').val(address);
    $('#province_id').val(province_id); // 标记下拉框一级区域的默认选项值
    $('#city_id').val(city_id); // 标记下拉框二级区域的默认选项值
    $('#area_id').val(area_id); // 标记下拉框三级区域的默认选项值
    $('#w2').children().find("select[id='region-province_id']").val(province_id);

    $('#w2').children().find("select[id='region-province_id']").trigger('change');
    });

});
JS;
$this->registerJs($js);
?>