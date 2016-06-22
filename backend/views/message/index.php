<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 11:42
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Member;

$yiiApp = Yii::$app;
$this->title = '消息推送';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
?>

<p></p>
<div class="modal-body">
    <div class="box-body">
        <button id="btnAdd" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
            <span class="glyphicon glyphicon-plus"></span>添加
        </button>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['modify'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'modifyForm'],
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'title',
                    [
                        'attribute' => 'uid',
                        'value' =>
                            function ($model) {
                                if($model->uid) {
                                    $member = Member::find()
                                        ->where(['id'=>$model->uid])
                                        ->one();
                                    $result = $member->real_name;
                                }
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'push_type',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['sendType'][$model->push_type];
                                return $result ?? '';
                            },
                    ],
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
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['sendOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'send_at',
                        'value' =>
                            function ($model) {
                                if($model->send_at) {
                                    $result = date('Y-m-d h:m:s', $model->send_at);
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
                                title="'.$model->title.'"
                                content="'.$model->content.'"
                                id="'.$model->id.'"
                                uid="'.$model->uid.'"
                                type="'.$model->push_type.'"
                                link_url="'.$model->link_url.'"
                                status="'.$model->status.'"
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
                <h4 class="modal-title"><label id="l_title">添加</label></h4>
            </div>
            <?=$this->render('_form', [
                'model' => $model,
            ]);?>
        </div>
    </div>
</div>
</div>

<?php
$formUrl = \yii\helpers\Url::toRoute('_form');
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
        var titles = '编辑';

        var id = $(this).attr('id');
        var title = $(this).attr('title');
        var content = $(this).attr('content');
        var link_url = $(this).attr('link_url');
        var type = $(this).attr('type');
        var status = $(this).attr('status');

        $("#rdo1").removeAttr("checked");
        $("#rdo2").removeAttr("checked");
        if(type == '1') {
            $("#rdo1").attr("checked","checked");
        } else{
            $("#rdo2").attr("checked","checked");
        }

        if(status == '1') {
            titles = '浏览';
            $("#btnSave").hide();
            $("#btnSend").hide();
        }

        $("#l_title").html(titles);
        $('#message-id').val(id);
        $('#message-content').val(content);
        $('#message-link_url').val(link_url);
        $('#message-title').val(title);
        $('#post_type').val('edit');
        //$('#myModal #tableForm').attr('action', '$formUrl?id='+id);

    });

    /*添加初始化*/
   $('#btnAdd').click(function() {
        var defaltData = '';
        $('#message-id').val(defaltData);
        $('#message-content').val(defaltData);
        $('#message-link_url').val(defaltData);
        $('#message-title').val(defaltData);
        //$('#myModal #tableForm').attr('action', '$formUrl');
        var valOption = $('input[name="push_type"]:checked').val();
        if(valOption == '0'){
            $("#rdo2").removeAttr("checked");
            $("#rdo1").attr("checked",true);
        }
        var title = '添加';
        $("#l_title").html(title);
        $('#post_type').val('add');

   });

JS;
$this->registerJs($js);
?>
