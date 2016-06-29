<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/28
 * Time: 11:06
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '管理员';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
?>
    <div class="modal-body">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
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
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => '序号'
                        ],
                        'username',
                        'email',
                        'address',
                        [
                            'attribute' => 'created_at',
                            'value' =>
                                function ($model) {
                                    if($model->created_at) {
                                        $result = date('Y-m-d h:m:s', $model->created_at);
                                    }
                                    return $result ?? '';
                                },
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' =>
                                function ($model) {
                                    if($model->updated_at) {
                                        $result = date('Y-m-d h:m:s', $model->updated_at);
                                    }
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
                                names="'.$model->username.'"
                                email="'.$model->email.'"
                                id="'.$model->id.'"
                                address="'.$model->address.'"
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
                    <h4 class="modal-title"><label id="l_title">管理员</label></h4>
                </div>
                <?=$this->render('form', [
                    'model' => $model,
                ]);?>
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
        var email = $(this).attr('email');

        $('#user-id').val(id);
        $('#userFormName').val(name);
        $('#userFormMail').val(email);
        $('#userFormAddress').val(address);
        $('#myModal #tableForm').attr('action', '$formUrl?id='+id);

    });

    /*添加初始化*/
   $('#btn_add').click(function() {
        var defaltData = '';
        $('#user-id').val(defaltData);
        $('#userFormName').val(defaltData);
        $('#userFormAddress').val(defaltData);
        $('#userFormMail').val(defaltData);
        $('#myModal #tableForm').attr('action', '$formUrl');

   });

JS;
$this->registerJs($js);
?>