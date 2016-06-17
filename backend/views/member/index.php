<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use common\models\Hospital;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProvider  */
/* @var $params */

$yiiApp = Yii::$app;
$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['delete'],
                'method' => 'post',
                'options' => ['class' => 'form-inline','id' => 'delForm'],
            ]); ?>
            <?= Html::input('hidden','type','enable',['id'=>'typeForm']); ?>
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
                                return $result ?? '';
                            },
                    ],
                    'province',
                    'city',
                    'area',
                    'created_at:date',
                    [
                        'attribute' => 'status',
                        'value'=>
                            function($model){
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}  {update} {delete}',
                        'header' => '操作',
                        'buttons'=>[
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#updateModal" data-toggle="modal"
                                data-id="'.$model->id.'"
                                data-real_name="'.$model->real_name.'"
                                data-nickname="'.$model->nickname.'"
                                data-username="'.$model->username.'"
                                data-email="'.$model->email.'"
                                data-hospital_id="'.$model->hospital_id.'"
                                data-rank_id="'.$model->rank_id.'"
                                data-status="'.$model->status.'"
                                data-province_id="'.$model->province_id.'"
                                data-city_id="'.$model->city_id.'"
                                data-area_id="'.$model->area_id.'"
                                data-province="'.$model->province.'"
                                data-city="'.$model->city.'"
                                data-area="'.$model->area.'"
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


<!-- 弹出层 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加用户</h4>
            </div>
            <?=$this->render('create');?>
        </div>
    </div>
</div>
    <div class="modal inmodal" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">编辑用户</h4>
                </div>
                <?=$this->render('update');?>
            </div>
        </div>
    </div>
    <div class="modal inmodal" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">导入用户</h4>
                </div>
                <?=$this->render('import');?>
            </div>
        </div>
    </div>
<?php

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
    
    $("span[name='saveData']").click(function(){
        var id = $(this).attr('data-id');
        var real_name = $(this).attr('data-real_name');
        var nickname = $(this).attr('data-nickname');
        var username = $(this).attr('data-username');
        var email = $(this).attr('data-email');
        var hospital_id = $(this).attr('data-hospital_id');
        var rank_id = $(this).attr('data-rank_id');
        var status = $(this).attr('data-status');
      
        /* 编辑初始化 */
        $('#updateModal #tableForm').attr('action','/member/form?id='+id);
        $('#updateModal #member-id').val(id);
        $('#updateModal #member-real_name').val(real_name);
        $('#updateModal #member-nickname').val(nickname);
        $('#updateModal #member-username').val(username);
        $('#updateModal #member-email').val(email);
        $('#updateModal #member-hospital_id').val(hospital_id);
        $('#updateModal #member-rank_id').val(rank_id);
        $('#updateModal #member-status').val(status);
        /*地区联动*/
        var regionValue = {};
        regionValue.province_id = $(this).attr('data-province_id');
        regionValue.city_id = $(this).attr('data-city_id');
        regionValue.area_id = $(this).attr('data-area_id');
        regionValue.province = $(this).attr('data-province');
        regionValue.city = $(this).attr('data-city');
        regionValue.area = $(this).attr('data-area');
        getRegionDefault(regionValue, 'tableForm');
    });
    /*添加初始化*/
   $('#btn_add').click(function() {
        /*地区联动*/
        getRegionInit('tableForm');
   });
JS;
$this->registerJs($js);
?>