<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 16:49
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
?>

<button type="button" class="btn btn-success"><span class="glyphicon-class"></span>添加</button>
<button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span>修改</button>
<button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash">删除</span></button>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <img src="img/11.png"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="img/11.png"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="img/11.png"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="img/11.png"
             alt="" class="thumbnail">
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
<!--            --><?//=$this->render('create', [
//                'model' => $model,
//            ]);?>
        </div>
    </div>
</div>
</div>