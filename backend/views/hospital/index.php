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
        <div class="modal-dialog" id="loadResult">
        </div>
    </div>
<?php
$js=<<<JS
$(document).ready(function(){alert(11);
/* 添加编辑单位 */
$(":button[name='doadd']").click(function(){
    //var tid = $(this).attr('tid');  // tid

    //$('div.chosen-container').attr("style","width:300px;");
    $("#loadResult").empty();
    $("#loadResult").load("/hospital/create", {pid: 1,type: 11}, function(){    });
    }
)

 $('.del').click(function () {
var _this = $(this);
var tid = $(_this).attr('tid');
    swal({
        title: "您确定要删除这条信息吗",
        text: "删除后将无法恢复，请谨慎操作！",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "取消",
        confirmButtonText: "删除",
        closeOnConfirm: false
    }, function () {
    $.post("/index.php?r=thread/delete", {tid:tid}, function(data){
    if(data.status==1)
    {
        $(_this).removeClass('btn-danger del').addClass('btn-default');
        $('#del_'+tid).html('后台删除');
        $('#del_'+tid).removeClass('btn-primary').addClass('btn-default');
        swal("删除成功！", "您已经永久删除了这条信息。", "success");
        $(_this).unbind("click"); //移除clic
    }
    else
        swal("删除失败！", "请联系管理员。", "error");
});
    });
});
});
JS;
$this->registerJs($js);
?>