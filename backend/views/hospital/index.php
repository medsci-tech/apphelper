<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '单位';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
?>
<p></p>
<div class="modal-body">
    <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel,'uploadModel'=>$uploadModel]); ?></div>
    <div class="box box-success">
        <div class="box-body">
            <?php
                $form = ActiveForm::begin([
                'action' => ['modify'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'modifyForm'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
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
                    'province',
                    'city',
                    'area',
                    'address',
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}  {update}',
                        'header' => '操作',
                        'buttons'=>[
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#myModal" data-toggle="modal"
                                names="'.$model->name.'"
                                address="'.$model->address.'"
                                id="'.$model->id.'"
                                province_id="'.$model->province_id.'"
                                city_id="'.$model->city_id.'"
                                area_id="'.$model->area_id.'"
                                province="'.$model->province.'"
                                city="'.$model->city.'"
                                area="'.$model->area.'"
                                 ></span>');
                            },
                        ]
                    ]
                ],
            ]); ?>
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
                <h4 class="modal-title"><label id="l_title">单位</label></h4>
            </div>
<?=$this->render('form', [
    'model' => $model,
]);?>
        </div>
    </div>
</div>
</div>
<?php
$formUrl = \yii\helpers\Url::toRoute('form');
$getError = $yiiApp->getSession()->getFlash('error');
$getSuccess = $yiiApp->getSession()->getFlash('success');
$js=<<<JS
    /*修改操作状态提示*/
    if('$getError' || '$getSuccess'){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
        }
    }
    if('$getError'){
        toastr.error('$getError');
    }else if('$getSuccess'){
        toastr.success('$getSuccess');
    }
    
    /*编辑初始化*/
    $("span[name='saveData']").click(function(){
        var id = $(this).attr('id');
        var name = $(this).attr('names');
        var address = $(this).attr('address');
        
        $('#hospital-id').val(id);
        $('#hospitalName').val(name);
        $('#hospital-address').val(address);
        $('#myModal #tableForm').attr('action', '$formUrl?id='+id);
       /*地区联动*/
        var regionValue = {};
        regionValue.province_id = $(this).attr('province_id');
        regionValue.city_id = $(this).attr('city_id');
        regionValue.area_id = $(this).attr('area_id');
        regionValue.province = $(this).attr('province');
        regionValue.city = $(this).attr('city');
        regionValue.area = $(this).attr('area');
        getRegionDefault(regionValue, 'tableForm');
    });
    
    /*添加初始化*/
   $('#btn_add').click(function() {
        var defaltData = ''; 
        $('#hospital-id').val(defaltData);
        $('#hospitalName').val(defaltData);
        $('#hospital-address').val(defaltData);
        $('#myModal #tableForm').attr('action', '$formUrl');
        /*地区联动*/
        getRegionInit('tableForm');
   });
   
JS;
$this->registerJs($js);
?>